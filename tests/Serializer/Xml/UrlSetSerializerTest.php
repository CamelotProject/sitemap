<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\UrlSet;
use Camelot\Sitemap\Serializer\Xml\UrlSetSerializer;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\UrlSetSerializer
 *
 * @internal
 */
final class UrlSetSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    public function testSerialize(): void
    {
        $url1 = new Url('https://sitemap.test/index.html');
        $url2 = new Url('https://sitemap.test/blog/index.html');

        $writer = $this->createWriterMock([
            [
                'name' => Sitemap::XML_CLARK_NS . 'url',
                'value' => $url1,
                'attributes' => [],
            ],
            [
                'name' => Sitemap::XML_CLARK_NS . 'url',
                'value' => $url2,
                'attributes' => [],
            ],
        ])
        ;

        UrlSetSerializer::serialize($writer, new UrlSet([$url1, $url2]));
    }
}
