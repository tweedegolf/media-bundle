<?php

namespace TweedeGolf\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('tweede_golf_media');
        if (\method_exists($treeBuilder, 'getRootNode')) {
            $rootNode = $treeBuilder->getRootNode();
        } else {
            // BC for symfony/config < 4.2
            $rootNode = $treeBuilder->root('tweede_golf_media');
        }
        $rootNode
            ->children()
                ->scalarNode('max_per_page')
                    ->defaultValue(18)
                ->end()
                ->scalarNode('file_entity')
                    ->isRequired()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
