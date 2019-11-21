<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\Image;
use Camelot\Sitemap\Serializer\Xml\ImageSerializer;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\ImageSerializer
 *
 * @internal
 */
final class ImageSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    private const BASE_META = [
        'name' => Sitemap::IMAGE_XML_CLARK_NS . 'loc',
        'value' => 'https://sitemap.test/mary.png',
        'attributes' => [],
    ];

    public function testSerialize(): void
    {
        $writer = $this->createWriterMock([self::BASE_META]);
        ImageSerializer::serialize($writer, new Image('https://sitemap.test/mary.png'));
    }

    public function testSerializeWithCaption(): void
    {
        $writer = $this->createWriterMock([
            self::BASE_META,
            [
                'name' => Sitemap::IMAGE_XML_CLARK_NS . 'caption',
                'value' => 'Something about Mary',
                'attributes' => [],
            ],
        ])
        ;

        ImageSerializer::serialize($writer, (new Image('https://sitemap.test/mary.png'))->setCaption('Something about Mary'));
    }

    public function testSerializeWithGeoLocation(): void
    {
        $writer = $this->createWriterMock([
            self::BASE_META,
            [
                'name' => Sitemap::IMAGE_XML_CLARK_NS . 'geo_location',
                'value' => 'Miami, Florida',
                'attributes' => [],
            ],
        ])
        ;

        ImageSerializer::serialize($writer, (new Image('https://sitemap.test/mary.png'))->setGeoLocation('Miami, Florida'));
    }

    public function testSerializeWithTitle(): void
    {
        $writer = $this->createWriterMock([
            self::BASE_META,
            [
                'name' => Sitemap::IMAGE_XML_CLARK_NS . 'title',
                'value' => 'There is something about Mary',
                'attributes' => [],
            ],
        ])
        ;

        ImageSerializer::serialize($writer, (new Image('https://sitemap.test/mary.png'))->setTitle('There is something about Mary'));
    }

    public function testSerializeWithLicence(): void
    {
        $writer = $this->createWriterMock([
            self::BASE_META,
            [
                'name' => Sitemap::IMAGE_XML_CLARK_NS . 'license',
                'value' => 'MIT',
                'attributes' => [],
            ],
        ])
        ;

        ImageSerializer::serialize($writer, (new Image('https://sitemap.test/mary.png'))->setLicense('MIT'));
    }
}
