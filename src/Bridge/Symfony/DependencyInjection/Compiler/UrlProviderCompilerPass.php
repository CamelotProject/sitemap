<?php

namespace Camelot\Sitemap\Bridge\Symfony\DependencyInjection\Compiler;

use Camelot\Sitemap\Sitemap;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Add all the services tagged "sitemap.provider" to the sitemap.
 */
class UrlProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasDefinition(Sitemap::class)) {
            return;
        }

        $definition = $container->getDefinition(Sitemap::class);

        foreach ($container->findTaggedServiceIds('sitemap.provider') as $id => $attributes) {
            $definition->addMethodCall('addProvider', [new Reference($id)]);
        }
    }
}
