<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),

            new Camelot\Sitemap\Bridge\Symfony\CamelotSitemapBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/config/config.yml');
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir().'/CamelotSitemapBundle/cache';
    }

    public function getLogDir()
    {
        return sys_get_temp_dir().'/CamelotSitemapBundle/logs';
    }
}
