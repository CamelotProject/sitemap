<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoUploader;
use Camelot\Sitemap\Serializer\Xml\VideoUploaderSerializer;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\VideoUploaderSerializer
 *
 * @internal
 */
final class VideoUploaderSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    public function testSerialize(): void
    {
        $writer = $this->createWriterMock([
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'uploader',
            'value' => 'Mary',
            'attributes' => [
                'info' => 'https://sitemap.test/mary.html',
            ],
        ])
        ;

        VideoUploaderSerializer::serialize($writer, new VideoUploader('Mary', 'https://sitemap.test/mary.html'));
    }
}
