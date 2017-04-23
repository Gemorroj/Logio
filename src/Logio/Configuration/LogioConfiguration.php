<?php
namespace Logio\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class LogioConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('logio');

        $rootNode
            ->children()->arrayNode('apache')->children()
            ->scalarNode('error_log_format')->isRequired()->end()
            ->scalarNode('error_log_path')->isRequired()->end()
            ->booleanNode('disabled')->defaultFalse()->end();

        $rootNode
            ->children()->arrayNode('nginx')->children()
            ->scalarNode('error_log_format')->isRequired()->end()
            ->scalarNode('error_log_path')->isRequired()->end()
            ->booleanNode('disabled')->defaultFalse()->end();

        $rootNode
            ->children()->arrayNode('php')->children()
            ->scalarNode('error_log_format')->isRequired()->end()
            ->scalarNode('error_log_path')->isRequired()->end()
            ->booleanNode('disabled')->defaultFalse()->end();

        $rootNode
            ->children()->arrayNode('mysql')->children()
            ->scalarNode('error_log_format')->isRequired()->end()
            ->scalarNode('error_log_path')->isRequired()->end()
            ->booleanNode('disabled')->defaultFalse()->end();

        return $treeBuilder;
    }
}
