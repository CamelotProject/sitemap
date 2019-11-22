<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Bridge\Symfony\DependencyInjection;

use Camelot\Sitemap\Sitemap;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @codeCoverageIgnore
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('camelot_sitemap');
        $treeBuilder
            ->getRootNode()
            ->children()
                ->scalarNode('host')->defaultNull()->end()
                ->scalarNode('file')->defaultValue('sitemap.xml')->end()
                ->enumNode('format')
                    ->values([Sitemap::XML, Sitemap::TXT])
                    ->defaultValue(Sitemap::XML)
                ->end()
                ->integerNode('limit')
                    ->defaultValue(50000)
                    ->validate()
                        ->ifTrue(function (int $v) { return $v < 0 || $v > 50000; })
                        ->thenInvalid('Limit must be a positive integer <= 50,000')
                    ->end()
                ->end()
                ->booleanNode('indexed')->defaultFalse()->end()
                ->booleanNode('compress')->defaultTrue()->end()
                ->arrayNode('providers')
                    ->addDefaultsIfNotSet()
                    ->append($this->getDataFileProviderNode())
                    ->append($this->getDoctrineProviderNode())
                    ->append($this->getSymfonyRouteProviderNode())
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function getDataFileProviderNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('data_file');
        $node = $treeBuilder->getRootNode();
        $children = $node
            ->addDefaultsIfNotSet()
            ->beforeNormalization()
                ->ifString()
                ->then(function (string $value) { return ['files' => [['file' => $value]]]; })
            ->end()
            ->children()
                ->arrayNode('files')
                    ->beforeNormalization()
                        ->ifString()
                        ->then(function (string $value) { return [['file' => $value]]; })
                    ->end()
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('file')->end()
                            ->scalarNode('route')->defaultNull()->end()
        ;
        $this->appendProviderDefaultNodes($children);

        return $node;
    }

    private function getDoctrineProviderNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('doctrine');
        $node = $treeBuilder->getRootNode();
        $children = $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('queries')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('entity')->isRequired()->end()
                            ->scalarNode('method')->end()
                            ->arrayNode('properties')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('lastmod')->defaultNull()->end()
                                    ->scalarNode('changefreq')->defaultNull()->end()
                                    ->scalarNode('priority')->defaultNull()->end()
                                    ->scalarNode('route_name')->defaultNull()->end()
                                    ->arrayNode('route_params')
                                        ->scalarPrototype()->end()
                                    ->end()
                                ->end()
                            ->end()
        ;
        $this->appendProviderDefaultNodes($children);
        $this->appendProviderRouteNodes($children);

        return $node;
    }

    private function getSymfonyRouteProviderNode(): ArrayNodeDefinition
    {
        $treeBuilder = new TreeBuilder('symfony');
        $node = $treeBuilder->getRootNode();
        $children = $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('routes')
                    ->arrayPrototype()
                        ->children()
        ;
        $this->appendProviderDefaultNodes($children);
        $this->appendProviderRouteNodes($children);

        return $node;
    }

    private function appendProviderDefaultNodes(NodeBuilder $children): void
    {
        $children
            ->scalarNode('lastmod')->end()
            ->scalarNode('changefreq')->end()
            ->floatNode('priority')->end()
        ;
    }

    private function appendProviderRouteNodes(NodeBuilder $children): void
    {
        $children
            ->arrayNode('route')
                ->isRequired()
                ->children()
                    ->scalarNode('name')->isRequired()->end()
                    ->arrayNode('params')
                        ->defaultValue([])
                        ->scalarPrototype()->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
