<?php

declare(strict_types = 1);

namespace Free2er\OAuth\DependencyInjection\Compiler;

use Free2er\OAuth\Service\UserService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Компилятор сервиса пользователей
 */
class UserServiceCompilerPass implements CompilerPassInterface
{
    /**
     * Инициализирует сервис
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $service = $container->getDefinition(UserService::class);

        foreach (array_keys($container->findTaggedServiceIds('oauth_server.authenticator')) as $authenticator) {
            $service->addMethodCall('addAuthenticator', [$container->findDefinition($authenticator)]);
        }
    }
}
