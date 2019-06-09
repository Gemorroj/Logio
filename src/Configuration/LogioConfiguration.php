<?php

namespace Logio\Configuration;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class LogioConfiguration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('logio');

        $rootNode = $treeBuilder->getRootNode();
        $rootNode->isRequired();

        $rootNode->append($this->getApacheNodeDefinition());
        $rootNode->append($this->getNginxNodeDefinition());
        $rootNode->append($this->getPhpFpmNodeDefinition());
        $rootNode->append($this->getPhpNodeDefinition());
        $rootNode->append($this->getMysqlNodeDefinition());

        return $treeBuilder;
    }

    protected function getApacheNodeDefinition(): NodeDefinition
    {
        $node = (new TreeBuilder('apache'))->getRootNode();
        $node->children()
            ->scalarNode('path')->isRequired()->cannotBeEmpty()->end()
            ->arrayNode('format')->children()
                ->scalarNode('date')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('type')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('client')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('message')->isRequired()->cannotBeEmpty()->end()
            ->end()->end()
            ->arrayNode('cast')->children()
                ->scalarNode('date')->end();

        return $node;
    }

    protected function getNginxNodeDefinition(): NodeDefinition
    {
        $node = (new TreeBuilder('nginx'))->getRootNode();
        $node->children()
            ->scalarNode('path')->isRequired()->cannotBeEmpty()->end()
            ->arrayNode('format')->children()
                ->scalarNode('date')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('type')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('message')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('client')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('server')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('request')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('host')->isRequired()->cannotBeEmpty()->end()
            ->end()->end()
            ->arrayNode('cast')->children()
                ->scalarNode('date')->end();

        return $node;
    }

    protected function getPhpFpmNodeDefinition(): NodeDefinition
    {
        $node = (new TreeBuilder('php_fpm'))->getRootNode();
        $node->children()
            ->scalarNode('path')->isRequired()->cannotBeEmpty()->end()
            ->arrayNode('format')->children()
            ->scalarNode('date')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('type')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('pool')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('child')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('message')->isRequired()->cannotBeEmpty()->end()
            ->end()->end()
            ->arrayNode('cast')->children()
            ->scalarNode('date')->end();

        return $node;
    }

    protected function getPhpNodeDefinition(): NodeDefinition
    {
        $node = (new TreeBuilder('php'))->getRootNode();
        $node->children()
            ->scalarNode('path')->isRequired()->cannotBeEmpty()->end()
            ->arrayNode('format')->children()
            ->scalarNode('date')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('type')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('message')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('file')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('line')->isRequired()->cannotBeEmpty()->end()
            ->end()->end()
            ->arrayNode('cast')->children()
            ->scalarNode('date')->end();

        return $node;
    }

    protected function getMysqlNodeDefinition(): NodeDefinition
    {
        $node = (new TreeBuilder('mysql'))->getRootNode();
        $node->children()
            ->scalarNode('path')->isRequired()->cannotBeEmpty()->end()
            ->arrayNode('format')->children()
            ->scalarNode('date')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('thread')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('type')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('message')->isRequired()->cannotBeEmpty()->end()
            ->end()->end()
            ->arrayNode('cast')->children()
            ->scalarNode('date')->end();

        return $node;
    }
}
