<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoContentSegmentLocation;
use Camelot\Sitemap\Serializer\Xml\VideoContentSegmentLocationSerializer;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\VideoContentSegmentLocationSerializer
 *
 * @internal
 */
final class VideoContentSegmentLocationSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    public function testSerialize(): void
    {
        $writer = $this->createWriterMock([
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'content_segment_loc',
            'value' => 'https://sitemap.test/video-1.ogg',
            'attributes' => [
                'duration' => 60,
            ],
        ]);

        VideoContentSegmentLocationSerializer::serialize($writer, new VideoContentSegmentLocation('https://sitemap.test/video-1.ogg', 60));
    }
}
