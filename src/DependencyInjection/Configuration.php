<?php

namespace Stringkey\BitMagicBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('stringkey_mapper');
//        $rootNode = $treeBuilder->getRootNode();
//        $rootNode->
//             children()
//                ->booleanNode('default_option')
//                    ->defaultTrue()
//                ->end()
//                ->booleanNode('optional_option')
//                ->end()
//            ->end();

        return $treeBuilder;
    }
}
