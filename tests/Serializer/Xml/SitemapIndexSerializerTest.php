<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child;
use Camelot\Sitemap\Element\SitemapIndex;
use Camelot\Sitemap\Serializer\Xml\SitemapIndexSerializer;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\SitemapIndexSerializer
 *
 * @internal
 */
final class SitemapIndexSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    public function testSerialize(): void
    {
        $sitemap1 = new Child\Sitemap('https://sitemap.test/sitemap-1.xml');
        $sitemap2 = new Child\Sitemap('https://sitemap.test/sitemap-2.xml');

        $writer = $this->createWriterMock([
            [
                'name' => Sitemap::XML_CLARK_NS . 'sitemap',
                'value' => $sitemap1,
                'attributes' => [],
            ],
            [
                'name' => Sitemap::XML_CLARK_NS . 'sitemap',
                'value' => $sitemap2,
                'attributes' => [],
            ],
        ]);

        SitemapIndexSerializer::serialize($writer, new SitemapIndex([$sitemap1, $sitemap2]));
    }
}
