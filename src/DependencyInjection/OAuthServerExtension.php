<?php

declare(strict_types = 1);

namespace Free2er\OAuth\DependencyInjection;

use DateInterval;
use Free2er\OAuth\Repository\RefreshTokenRepositoryInterface;
use Free2er\OAuth\Service\AccessTokenService;
use Free2er\OAuth\Service\ClientService;
use Free2er\OAuth\Service\ScopeService;
use Lcobucci\JWT\Signer;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
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
        $this->injectAuthorizationServer($container, $config);
        $this->injectClientCredentialsGrant($container);
        $this->injectPasswordGrant($container, $config);
        $this->injectAuthorizationCodeGrant($container, $config);
        $this->injectRefreshTokenGrant($container, $config);

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
     * Включает сервер авторизации
     *
     * @param ContainerBuilder $container
     * @param array             $config
     */
    private function injectAuthorizationServer(ContainerBuilder $container, array $config): void
    {
        $privateKey = new Definition(CryptKey::class, [
            $config['private_key'],
            $config['private_key_password'],
            false
        ]);

        $server = new Definition(AuthorizationServer::class, [
            new Reference(ClientService::class),
            new Reference(AccessTokenService::class),
            new Reference(ScopeService::class),
            $privateKey,
            $config['encryption_key']
        ]);

        $accessTokenTTL = new Definition(DateInterval::class, [$config['ttl']['access_token']]);

        foreach ($config['grant_types'] as $grant) {
            $server->addMethodCall('enableGrantType', [new Reference($grant), $accessTokenTTL]);
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
     * @param array            $config
     */
    private function injectPasswordGrant(ContainerBuilder $container, array $config): void
    {
        // TODO: not implemented yet
    }

    /**
     * Включает авторизацию пользователей по коду авторизации
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function injectAuthorizationCodeGrant(ContainerBuilder $container, array $config): void
    {
        // TODO: not implemented yet
    }

    /**
     * Включает продление срока действия ключа доступа
     *
     * @param ContainerBuilder $container
     * @param array            $config
     */
    private function injectRefreshTokenGrant(ContainerBuilder $container, array $config): void
    {
        $refreshTokenRepository = new Reference(RefreshTokenRepositoryInterface::class);
        $refreshTokenTTL        = new Definition(DateInterval::class, [$config['ttl']['refresh_token']]);

        $grant = new Definition(RefreshTokenGrant::class, [$refreshTokenRepository]);
        $grant->addMethodCall('setRefreshTokenTTL', [$refreshTokenTTL]);

        $container->setDefinition(RefreshTokenGrant::class, $grant);
    }
}
