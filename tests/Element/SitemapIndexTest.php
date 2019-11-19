<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element;

use Camelot\Sitemap\Element\Child\Sitemap;
use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\SitemapIndex;
use Camelot\Sitemap\Element\UrlSet;
use PHPUnit\Framework\TestCase;
use function iter\toArray;

/**
 * @covers \Camelot\Sitemap\Element\SitemapIndex
 *
 * @internal
 */
final class SitemapIndexTest extends TestCase
{
    public function providerChildren(): iterable
    {
        yield [[]];
        yield [[new Sitemap('https://sitemap.test/sitemap-1.xml')]];
        yield [[new Sitemap('https://sitemap.test/sitemap-1.xml'), new Sitemap('https://sitemap.test/sitemap-2.xml')]];
    }

    /**
     * @dataProvider providerChildren
     */
    public function testChildren(iterable $sitemaps): void
    {
        $sitemapIndex = new SitemapIndex($sitemaps);

        static::assertSame(toArray($sitemaps), toArray($sitemapIndex->getChildren()));
    }

    /**
     * @dataProvider providerChildren
     */
    public function testSetChildren(iterable $sitemaps): void
    {
        $sitemapIndex = new SitemapIndex();

        static::assertSame(toArray($sitemaps), toArray($sitemapIndex->setChildren($sitemaps)->getChildren()));
    }

    /**
     * @dataProvider providerChildren
     */
    public function testAddChild(iterable $sitemaps): void
    {
        $sitemapIndex = new SitemapIndex();
        foreach ($sitemaps as $url) {
            $sitemapIndex->addChild($url);
        }

        static::assertSame(toArray($sitemaps), toArray($sitemapIndex->getChildren()));
    }

    public function providerGrandChildren(): iterable
    {
        $url1 = new Url('https://sitemap.test/index.html');
        $url2 = new Url('https://sitemap.test/blog/index.html');

        yield [[]];
        yield [[new UrlSet([$url1])]];
        yield [[new UrlSet([$url1, $url2])]];
    }

    /**
     * @dataProvider providerGrandChildren
     */
    public function testGrandChildren(iterable $urlSets): void
    {
        $sitemapIndex = new SitemapIndex([], $urlSets);

        static::assertSame(toArray($urlSets), toArray($sitemapIndex->getGrandChildren()));
    }

    /**
     * @dataProvider providerGrandChildren
     */
    public function testSetGrandChildren(iterable $urlSets): void
    {
        $sitemapIndex = new SitemapIndex();

        static::assertSame(toArray($urlSets), toArray($sitemapIndex->setGrandChildren($urlSets)->getGrandChildren()));
    }

    /**
     * @dataProvider providerGrandChildren
     */
    public function testAddGrandChild(iterable $urlSets): void
    {
        $sitemapIndex = new SitemapIndex();
        foreach ($urlSets as $urlSet) {
            $sitemapIndex->addGrandChild($urlSet);
        }

        static::assertSame(toArray($urlSets), toArray($sitemapIndex->getGrandChildren()));
    }
}
