<?php

namespace Camelot\Sitemap\Bridge\Symfony;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

use Camelot\Sitemap\Bridge\Symfony\DependencyInjection\Compiler\UrlProviderCompilerPass;


class CamelotSitemapBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new UrlProviderCompilerPass());
    }
}
