<?php

namespace Camelot\Sitemap\Bridge\Symfony\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;

class CamelotSitemapExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('sitemap.config.base_host', $config['base_host']);
        $container->setParameter('sitemap.config.base_host_sitemap', $config['base_host_sitemap']);
        $container->setParameter('sitemap.config.limit', $config['limit']);
        $container->setParameter('sitemap.config.file', $config['file']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
