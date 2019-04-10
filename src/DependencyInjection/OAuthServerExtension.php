<?php

declare(strict_types = 1);

namespace Free2er\OAuth\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

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
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        // TODO: not implemented yet
    }
}
