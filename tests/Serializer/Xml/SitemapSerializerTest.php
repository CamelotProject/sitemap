<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child;
use Camelot\Sitemap\Serializer\Xml\SitemapSerializer;
use Camelot\Sitemap\Sitemap;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\SitemapSerializer
 *
 * @internal
 */
final class SitemapSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    private const BASE_META = [
        'name' => Sitemap::XML_CLARK_NS . 'loc',
        'value' => 'https://sitemap.test/sitemap-1.xml',
        'attributes' => [],
    ];

    public function testSerialize(): void
    {
        $writer = $this->createWriterMock([self::BASE_META]);
        SitemapSerializer::serialize($writer, new Child\Sitemap('https://sitemap.test/sitemap-1.xml'));
    }

    public function testSerializeWithCaption(): void
    {
        $lastModified = new DateTimeImmutable('2000-01-01');

        $writer = $this->createWriterMock([
            self::BASE_META,
            [
                'name' => Sitemap::XML_CLARK_NS . 'lastmod',
                'value' => $lastModified->format(DateTimeInterface::W3C),
                'attributes' => [],
            ],
        ])
        ;

        SitemapSerializer::serialize($writer, (new Child\Sitemap('https://sitemap.test/sitemap-1.xml'))->setLastModified($lastModified));
    }
}
