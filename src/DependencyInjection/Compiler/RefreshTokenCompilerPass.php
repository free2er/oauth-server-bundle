<?php

declare(strict_types = 1);

namespace Free2er\OAuth\DependencyInjection\Compiler;

use Free2er\OAuth\Service\RefreshTokenService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Компилятор зависимостей от ключа продления доступа
 */
class RefreshTokenCompilerPass implements CompilerPassInterface
{
    /**
     * Инициализирует сервис
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        foreach (array_keys($container->findTaggedServiceIds('oauth_server.refresh_token')) as $grant) {
            $grant = $container->findDefinition($grant);
            $grant->addMethodCall('setRefreshTokenRepository', [new Reference(RefreshTokenService::class)]);
            $grant->addMethodCall('setRefreshTokenTTL', [new Reference('oauth_server.refresh_token_ttl')]);
        }
    }
}
