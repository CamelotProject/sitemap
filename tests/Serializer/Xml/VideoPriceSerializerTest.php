<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoPrice;
use Camelot\Sitemap\Serializer\Xml\VideoPriceSerializer;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\VideoPriceSerializer
 *
 * @internal
 */
final class VideoPriceSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    public function testSerialize(): void
    {
        $writer = $this->createWriterMock([
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'price',
            'value' => 42.24,
            'attributes' => [
                'currency' => 'EUR',
                'type' => VideoPrice::TYPE_PURCHASE,
                'resolution' => VideoPrice::RESOLUTION_HIGH,
            ],
        ]);

        VideoPriceSerializer::serialize($writer, new VideoPrice('EUR', 42.24, VideoPrice::TYPE_PURCHASE, VideoPrice::RESOLUTION_HIGH));
    }
}
