<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Bridge\Symfony\DependencyInjection;

use Camelot\Sitemap\Bridge\Symfony\DependencyInjection\CamelotSitemapExtension;
use Camelot\Sitemap\Provider\SymfonyRouteProvider;
use Camelot\Sitemap\Tests\Fixtures\ReferenceConfiguration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Camelot\Sitemap\Bridge\Symfony\DependencyInjection\CamelotSitemapExtension
 *
 * @internal
 */
final class CamelotSitemapExtensionTest extends TestCase
{
    public function testLoadConfigurationFileProviderTags(): void
    {
        static::assertArrayHasKey('camelot.sitemap.provider.config_file_0', $this->getBuiltContainer()->findTaggedServiceIds('camelot.sitemap.provider'));
    }

    public function testLoadDoctrineProviderTags(): void
    {
        static::assertArrayHasKey('camelot.sitemap.provider.doctrine_0', $this->getBuiltContainer()->findTaggedServiceIds('camelot.sitemap.provider'));
    }

    public function testLoadRouteProviderTags(): void
    {
        static::assertArrayHasKey(SymfonyRouteProvider::class, $this->getBuiltContainer()->findTaggedServiceIds('camelot.sitemap.provider'));
    }

    private function getBuiltContainer(): ContainerBuilder
    {
        $container = new ContainerBuilder();
        $extension = new CamelotSitemapExtension();
        $config = ReferenceConfiguration::get();
        $extension->load([$config], $container);

        return $container;
    }
}
