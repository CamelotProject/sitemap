<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Text;

use Camelot\Sitemap\Element\Child\Sitemap;
use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\RootElementInterface;
use Camelot\Sitemap\Element\SitemapIndex;
use Camelot\Sitemap\Element\UrlSet;
use Camelot\Sitemap\Exception\SerializerException;
use Camelot\Sitemap\Serializer\Text\SitemapIndexSerializer;
use PHPUnit\Framework\TestCase;
use const PHP_EOL;

/**
 * @covers \Camelot\Sitemap\Serializer\Text\SitemapIndexSerializer
 *
 * @internal
 */
final class SitemapIndexSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $expected = 'https://sitemap.test/sitemap-1.xml' . PHP_EOL;
        $url1 = new Url('https://sitemap.test/index.html');
        $url2 = new Url('https://sitemap.test/blog/index.html');
        $urlSet = new UrlSet([$url1, $url2]);
        $sitemap = new Sitemap('https://sitemap.test/sitemap-1.xml');
        $sitemapIndex = new SitemapIndex([$sitemap], [$urlSet]);
        $serializer = new SitemapIndexSerializer();

        static::assertSame($expected, $serializer->serialize($sitemapIndex));
    }

    public function testSerializeNotUrlSet(): void
    {
        $this->expectException(SerializerException::class);

        $sitemaps = $this->createMock(RootElementInterface::class);
        $serializer = new SitemapIndexSerializer();

        $serializer->serialize($sitemaps);
    }
}
