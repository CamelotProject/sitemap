<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoTvShow;
use Camelot\Sitemap\Serializer\Xml\VideoTvShowSerializer;
use Camelot\Sitemap\Sitemap;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\VideoTvShowSerializer
 *
 * @internal
 */
final class VideoTvShowSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    public function testSerialize(): void
    {
        $writer = $this->createWriterMock([
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'tvshow',
            'value' => [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'show_title',
                    'value' => 'My TV Show',
                    'attributes' => [],
                ],
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'video_type',
                    'value' => 'full',
                    'attributes' => [],
                ],
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'episode_title',
                    'value' => 'Season 2, Episode 1',
                    'attributes' => [],
                ],
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'season_number',
                    'value' => 2,
                    'attributes' => [],
                ],
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'episode_number',
                    'value' => 1,
                    'attributes' => [],
                ],
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'premier_date',
                    'value' => '2020-01-01T00:00:00+00:00',
                    'attributes' => [],
                ],
            ],
            'attributes' => [],
        ]);

        VideoTvShowSerializer::serialize($writer, new VideoTvShow('My TV Show', 'full', 'Season 2, Episode 1', 2, 1, new DateTimeImmutable('2020-01-01')));
    }
}
