<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoPlayerLocation;
use Camelot\Sitemap\Serializer\Xml\VideoPlayerLocationSerializer;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\VideoPlayerLocationSerializer
 *
 * @internal
 */
final class VideoPlayerLocationSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    public function testSerialize(): void
    {
        $playerLocation = new VideoPlayerLocation('https://sitemap.test/video.php');

        $writer = $this->createWriterMock([
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'player_loc',
            'value' => 'https://sitemap.test/video.php',
            'attributes' => [],
        ]);

        VideoPlayerLocationSerializer::serialize($writer, $playerLocation);
    }

    public function providerAllowEmbed(): iterable
    {
        yield ['yes', true];
        yield ['no', false];
    }

    /**
     * @dataProvider providerAllowEmbed
     */
    public function testSerializeWithAllowEmbed(string $expected, bool $allowEmbed): void
    {
        $playerLocation = new VideoPlayerLocation('https://sitemap.test/video.php', $allowEmbed);

        $writer = $this->createWriterMock([
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'player_loc',
            'value' => 'https://sitemap.test/video.php',
            'attributes' => ['allow_embed' => $expected],
        ]);

        VideoPlayerLocationSerializer::serialize($writer, $playerLocation);
    }
}
