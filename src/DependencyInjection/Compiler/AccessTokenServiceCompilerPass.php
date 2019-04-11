<?php

declare(strict_types = 1);

namespace Free2er\OAuth\DependencyInjection\Compiler;

use Free2er\OAuth\Service\AccessTokenService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Компилятор сервиса ключей доступа
 */
class AccessTokenServiceCompilerPass implements CompilerPassInterface
{
    /**
     * Инициализирует сервис
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $service = $container->getDefinition(AccessTokenService::class);

        foreach (array_keys($container->findTaggedServiceIds('oauth_server.claim_provider')) as $provider) {
            $service->addMethodCall('addProvider', [$container->findDefinition($provider)]);
        }
    }
}
