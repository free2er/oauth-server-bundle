<?php

declare(strict_types = 1);

namespace Free2er\OAuth\DependencyInjection;

use Lcobucci\JWT\Signer\Rsa\Sha512;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Конфигурация модуля
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Возвращает строителя конфигурации
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();

        $root = $builder->root('oauth_server');
        $root
            ->children()
                ->scalarNode('public_key')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('scope_key')->defaultValue('scopes')->end()
                ->scalarNode('algorithm')->defaultValue(Sha512::class)->end()
            ->end();

        // TODO: not implemented yet

        return $builder;
    }
}
