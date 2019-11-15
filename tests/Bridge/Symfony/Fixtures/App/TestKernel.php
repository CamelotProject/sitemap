<?php

namespace Camelot\Sitemap\Tests\Bridge\Symfony\Fixtures\App;

use Camelot\Sitemap\Bridge\Symfony\CamelotSitemapBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new CamelotSitemapBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/config/config.yml');
    }

    public function getCacheDir()
    {
        return __DIR__ . '/var/cache';
    }

    public function getLogDir()
    {
        return __DIR__ . '/var/logs';
    }
}
