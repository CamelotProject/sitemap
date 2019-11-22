<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Bridge\Symfony\DependencyInjection;

use Camelot\Sitemap\Config;
use Camelot\Sitemap\Provider\DataFileProvider;
use Camelot\Sitemap\Provider\DoctrineProvider;
use Camelot\Sitemap\Provider\SymfonyRouteProvider;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

final class CamelotSitemapExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition(Config::class);
        $definition->setArgument('$host', $config['host']);
        $definition->setArgument('$fileName', $config['file']);
        $definition->setArgument('$fileBasePath', '%kernel.project_dir%/public');
        $definition->setArgument('$limit', $config['limit']);
        $definition->setArgument('$compress', $config['compress']);

        foreach ($config['providers']['data_file']['files'] as $index => $query) {
            $definition = new Definition(DataFileProvider::class);
            $definition
                ->setAutoconfigured(true)
                ->setAutowired(true)
                ->setTags(['camelot.sitemap.provider' => [[]]])
                ->setArgument('$options', $query)
            ;
            $container->setDefinition('camelot.sitemap.provider.config_file_' . $index, $definition);
        }

        foreach ($config['providers']['doctrine']['queries'] as $index => $query) {
            $definition = new Definition(DoctrineProvider::class);
            $definition
                ->setAutoconfigured(true)
                ->setAutowired(true)
                ->setTags(['camelot.sitemap.provider' => [[]]])
                ->setArgument('$options', $query)
            ;
            $container->setDefinition('camelot.sitemap.provider.doctrine_' . $index, $definition);
        }

        if ($config['providers']['symfony']['routes']) {
            $definition = $container->getDefinition(SymfonyRouteProvider::class);
            $definition->setArgument('$options', $config['providers']['symfony']['routes']);
            $definition->setTags(['camelot.sitemap.provider' => [[]]]);
        }
    }
}
