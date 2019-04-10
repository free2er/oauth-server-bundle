<?php

declare(strict_types = 1);

namespace Free2er\OAuth\DependencyInjection\Compiler;

use Free2er\OAuth\Service\ScopeService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Компилятор сервиса прав доступа
 */
class ScopeServiceCompilerPass implements CompilerPassInterface
{
    /**
     * Инициализирует сервис
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $service = $container->getDefinition(ScopeService::class);

        foreach (array_keys($container->findTaggedServiceIds('oauth_scope_provider')) as $provider) {
            $service->addMethodCall('addProvider', [$container->findDefinition($provider)]);
        }
    }
}
