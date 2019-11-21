<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoPlatform;
use Camelot\Sitemap\Serializer\Xml\VideoPlatformSerializer;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;
use function implode;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\VideoPlatformSerializer
 *
 * @internal
 */
final class VideoPlatformSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    public function testSerialize(): void
    {
        $writer = $this->createWriterMock([
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'platform',
            'value' => implode(' ', [VideoPlatform::MOBILE, VideoPlatform::TV, VideoPlatform::WEB]),
            'attributes' => [
                'relationship' => VideoPlatform::ALLOW,
            ],
        ])
        ;

        VideoPlatformSerializer::serialize($writer, new VideoPlatform(VideoPlatform::ALLOW, [VideoPlatform::MOBILE, VideoPlatform::TV, VideoPlatform::WEB]));
    }
}
