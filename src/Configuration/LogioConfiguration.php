<?php

namespace Logio\Configuration;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class LogioConfiguration implements ConfigurationInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('logio');

        $rootNode = $treeBuilder->getRootNode();

        if (isset($this->data['apache'])) {
            $rootNode->append($this->getApacheBuilder());
        }

        if (isset($this->data['nginx'])) {
            $rootNode->append($this->getNginxBuilder());
        }

        if (isset($this->data['php_fpm'])) {
            $rootNode->append($this->getPhpFpmBuilder());
        }

        if (isset($this->data['php'])) {
            $rootNode->append($this->getPhpBuilder());
        }

        if (isset($this->data['mysql'])) {
            $rootNode->append($this->getMysqlBuilder());
        }

        return $treeBuilder;
    }

    protected function getApacheBuilder(): NodeDefinition
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

    protected function getNginxBuilder(): NodeDefinition
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

    protected function getPhpFpmBuilder(): NodeDefinition
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

    protected function getPhpBuilder(): NodeDefinition
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

    protected function getMysqlBuilder(): NodeDefinition
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
