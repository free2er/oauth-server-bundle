<?php

declare(strict_types = 1);

namespace Free2er\OAuth\DependencyInjection;

use DateInterval;
use Free2er\OAuth\Service\AccessTokenService;
use Free2er\OAuth\Service\AuthorizationCodeService;
use Free2er\OAuth\Service\ClientService;
use Free2er\OAuth\Service\RefreshTokenService;
use Free2er\OAuth\Service\ScopeService;
use Free2er\OAuth\Service\UserService;
use Lcobucci\JWT\Signer;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Throwable;

/**
 * Расширение модуля
 */
class OAuthServerExtension extends Extension
{
    /**
     * Загружает конфигурацию модуля
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws Throwable
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $this->injectSigner($container, $config);
        $this->injectTtl($container, $config);
        $this->injectAuthorizationServer($container, $config);
        $this->injectClientCredentialsGrant($container);
        $this->injectPasswordGrant($container);
        $this->injectAuthorizationCodeGrant($container);
        $this->injectRefreshTokenGrant($container);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');
    }

    /**
     * Включает шифратор JWT
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function injectSigner(ContainerBuilder $container, array $config): void
    {
        $signer = new Definition($config['algorithm']);
        $container->setDefinition(Signer::class, $signer);
    }

    /**
     * Включает сроки действия ключей доступа
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function injectTtl(ContainerBuilder $container, array $config): void
    {
        foreach ($config['ttl'] as $name => $time) {
            $name = sprintf('oauth_server.%s_ttl', $name);
            $time = new Definition(DateInterval::class, [$time]);

            $container->setDefinition($name, $time);
        }
    }

    /**
     * Включает сервер авторизации
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function injectAuthorizationServer(ContainerBuilder $container, array $config): void
    {
        $privateKey = new Definition(CryptKey::class, [
            $config['private_key'],
            $config['private_key_password'],
            false,
        ]);

        $server = new Definition(AuthorizationServer::class, [
            new Reference(ClientService::class),
            new Reference(AccessTokenService::class),
            new Reference(ScopeService::class),
            $privateKey,
            $config['encryption_key'],
        ]);

        foreach ($config['grants'] as $grant) {
            $server->addMethodCall('enableGrantType', [
                new Reference($grant),
                new Reference('oauth_server.access_token_ttl'),
            ]);
        }

        $container->setDefinition(AuthorizationServer::class, $server);
    }

    /**
     * Включает авторизацию клиентов
     *
     * @param ContainerBuilder $container
     */
    private function injectClientCredentialsGrant(ContainerBuilder $container): void
    {
        $grant = new Definition(ClientCredentialsGrant::class);
        $container->setDefinition(ClientCredentialsGrant::class, $grant);
    }

    /**
     * Включает авторизацию пользователей
     *
     * @param ContainerBuilder $container
     */
    private function injectPasswordGrant(ContainerBuilder $container): void
    {
        $dependencies = [
            new Reference(UserService::class),
            new Reference(RefreshTokenService::class),
        ];

        $grant = new Definition(PasswordGrant::class, $dependencies);
        $grant->addTag('oauth_server.refresh_token');

        $container->setDefinition(PasswordGrant::class, $grant);
    }

    /**
     * Включает авторизацию пользователей по коду авторизации
     *
     * @param ContainerBuilder $container
     */
    private function injectAuthorizationCodeGrant(ContainerBuilder $container): void
    {
        $dependencies = [
            new Reference(AuthorizationCodeService::class),
            new Reference(RefreshTokenService::class),
            new Reference('oauth_server.authorization_code_ttl'),
        ];

        $grant = new Definition(AuthCodeGrant::class, $dependencies);
        $grant->addTag('oauth_server.refresh_token');

        $container->setDefinition(AuthCodeGrant::class, $grant);
    }

    /**
     * Включает продление срока действия ключа доступа
     *
     * @param ContainerBuilder $container
     */
    private function injectRefreshTokenGrant(ContainerBuilder $container): void
    {
        $dependencies = [new Reference(RefreshTokenService::class)];

        $grant = new Definition(RefreshTokenGrant::class, $dependencies);
        $grant->addTag('oauth_server.refresh_token');

        $container->setDefinition(RefreshTokenGrant::class, $grant);
    }
}
