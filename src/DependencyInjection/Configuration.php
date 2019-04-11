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

        $root = $builder->root('o_auth_server');
        $root
            ->children()
                ->scalarNode('encryption_key')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('private_key')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('private_key_password')->defaultNull()->end()
                ->scalarNode('scope_key')->defaultValue('scopes')->end()
                ->scalarNode('algorithm')->defaultValue(Sha512::class)->end()
                ->arrayNode('ttl')
                    ->children()
                        ->scalarNode('access_token')->defaultValue('PT1H')->end()
                        ->scalarNode('authorization_code')->defaultValue('PT1H')->end()
                        ->scalarNode('refresh_token')->defaultValue('P1M')->end()
                    ->end()
                ->end()
                ->arrayNode('grants')
                    ->scalarPrototype()->cannotBeEmpty()->end()
                ->end()
            ->end();

        return $builder;
    }
}
