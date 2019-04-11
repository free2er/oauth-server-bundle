<?php

declare(strict_types = 1);

namespace Free2er\OAuth;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Модуль сервера авторизации OAuth
 */
class OAuthServerBundle extends Bundle
{
    /**
     * Устанавливает параметры сервис-контейнера
     *
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DependencyInjection\Compiler\AccessTokenServiceCompilerPass());
        $container->addCompilerPass(new DependencyInjection\Compiler\RefreshTokenCompilerPass());
        $container->addCompilerPass(new DependencyInjection\Compiler\ScopeServiceCompilerPass());
        $container->addCompilerPass(new DependencyInjection\Compiler\UserServiceCompilerPass());
    }
}
