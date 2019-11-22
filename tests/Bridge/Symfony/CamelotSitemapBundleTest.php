<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Bridge\Symfony;

use Camelot\Sitemap\Bridge\Symfony\CamelotSitemapBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers \Camelot\Sitemap\Bridge\Symfony\CamelotSitemapBundle
 *
 * @internal
 */
final class CamelotSitemapBundleTest extends TestCase
{
    public function testBuild(): void
    {
        $container = new ContainerBuilder();
        $bundle = new CamelotSitemapBundle();
        $bundle->build($container);

        $this->addToAssertionCount(1);
    }
}
