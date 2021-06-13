<?php
declare(strict_types=1);

namespace Vim\Settings\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('settings');

        $treeBuilder
            ->getRootNode()
            ->children()
                ->scalarNode('pool')->end()
                ->arrayNode('settings')
                    ->variablePrototype()
                    ->defaultValue([])
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
