<?php

namespace Bam1to\AuditTrailBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('audit_trail');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('tables')
                    ->arrayPrototype()
                        ->children()
                            ->arrayNode('table')
                                ->children()
                                    ->scalarNode('name')->end()
                                    ->scalarNode('actions')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        
        return $treeBuilder;
    }
}
