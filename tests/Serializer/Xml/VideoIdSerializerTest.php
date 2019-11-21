<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoId;
use Camelot\Sitemap\Serializer\Xml\VideoIdSerializer;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\VideoIdSerializer
 *
 * @internal
 */
final class VideoIdSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    public function testSerialize(): void
    {
        $writer = $this->createWriterMock([
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'id',
            'value' => '0d2c6e71-9ac3-4ef4-a6c2-899562e19ddc',
            'attributes' => [
                'type' => VideoId::TYPE_TMS_PROGRAM,
            ],
        ]);

        VideoIdSerializer::serialize($writer, new VideoId('0d2c6e71-9ac3-4ef4-a6c2-899562e19ddc', VideoId::TYPE_TMS_PROGRAM));
    }
}
