<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoRestriction;
use Camelot\Sitemap\Serializer\Xml\VideoRestrictionSerializer;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\VideoRestrictionSerializer
 *
 * @internal
 */
final class VideoRestrictionSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    public function testSerialize(): void
    {
        $writer = $this->createWriterMock([
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'restriction',
            'value' => 'au nl fr',
            'attributes' => [
                'relationship' => VideoRestriction::ALLOW,
            ],
        ])
        ;

        VideoRestrictionSerializer::serialize($writer, new VideoRestriction(VideoRestriction::ALLOW, ['au', 'nl', 'fr']));
    }
}
