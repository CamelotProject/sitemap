<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoGalleryLocation;
use Camelot\Sitemap\Serializer\Xml\VideoGalleryLocationSerializer;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\VideoGalleryLocationSerializer
 *
 * @internal
 */
final class VideoGalleryLocationSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    public function testSerialize(): void
    {
        $playerLocation = new VideoGalleryLocation('https://sitemap.test/gallery');

        $writer = $this->createWriterMock([
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'gallery_loc',
            'value' => 'https://sitemap.test/gallery',
            'attributes' => [],
        ]);

        VideoGalleryLocationSerializer::serialize($writer, $playerLocation);
    }

    public function testSerializeWithTitle(): void
    {
        $playerLocation = new VideoGalleryLocation('https://sitemap.test/gallery', 'Our gallery');

        $writer = $this->createWriterMock([
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'gallery_loc',
            'value' => 'https://sitemap.test/gallery',
            'attributes' => ['title' => 'Our gallery'],
        ]);

        VideoGalleryLocationSerializer::serialize($writer, $playerLocation);
    }
}
