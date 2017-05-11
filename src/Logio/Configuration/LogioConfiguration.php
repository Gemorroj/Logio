<?php
namespace Logio\Configuration;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class LogioConfiguration implements ConfigurationInterface
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('logio');

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
            $rootNode
                ->children()->arrayNode('mysql')->children()
                ->scalarNode('path')->isRequired()->cannotBeEmpty()->end();
        }

        return $treeBuilder;
    }

    protected function getApacheBuilder()
    {
        $node = (new TreeBuilder())->root('apache');
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

    protected function getNginxBuilder()
    {
        $node = (new TreeBuilder())->root('nginx');
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

    protected function getPhpFpmBuilder()
    {
        $node = (new TreeBuilder())->root('php_fpm');
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

    protected function getPhpBuilder()
    {
        $node = (new TreeBuilder())->root('php');
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
}