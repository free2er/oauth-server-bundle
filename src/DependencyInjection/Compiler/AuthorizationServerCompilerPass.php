<?php

declare(strict_types = 1);

namespace Free2er\OAuth\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Компилятор сервера авторизации
 */
class AuthorizationServerCompilerPass implements CompilerPassInterface
{
    /**
     * Устанавливает параметры сервис-контейнера
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        // TODO: not implemented yet
    }
}
