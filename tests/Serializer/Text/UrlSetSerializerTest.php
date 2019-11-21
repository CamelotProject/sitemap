<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Text;

use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\RootElementInterface;
use Camelot\Sitemap\Element\UrlSet;
use Camelot\Sitemap\Exception\SerializerException;
use Camelot\Sitemap\Serializer\Text\UrlSetSerializer;
use PHPUnit\Framework\TestCase;
use const PHP_EOL;

/**
 * @covers \Camelot\Sitemap\Serializer\Text\UrlSetSerializer
 *
 * @internal
 */
final class UrlSetSerializerTest extends TestCase
{
    public function testSerialize(): void
    {
        $expected = 'https://sitemap.test/index.html' . PHP_EOL . 'https://sitemap.test/blog/index.html' . PHP_EOL;

        $url1 = new Url('https://sitemap.test/index.html');
        $url2 = new Url('https://sitemap.test/blog/index.html');
        $urlSet = new UrlSet([$url1, $url2]);
        $serializer = new UrlSetSerializer();

        static::assertSame($expected, $serializer->serialize($urlSet));
    }

    public function testSerializeNotUrlSet(): void
    {
        $this->expectException(SerializerException::class);

        $urlSet = $this->createMock(RootElementInterface::class);
        $serializer = new UrlSetSerializer();

        $serializer->serialize($urlSet);
    }
}
