<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoTvShow;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;

final class VideoTvShowSerializer implements XmlSerializerInterface
{
    /** @param VideoTvShow $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'tvshow',
            'value' => [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'show_title',
                    'value' => $object->getShowTitle(),
                    'attributes' => [],
                ],
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'video_type',
                    'value' => $object->getVideoType(),
                    'attributes' => [],
                ],
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'episode_title',
                    'value' => $object->getEpisodeTitle(),
                    'attributes' => [],
                ],
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'season_number',
                    'value' => $object->getSeasonNumber(),
                    'attributes' => [],
                ],
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'episode_number',
                    'value' => $object->getEpisodeNumber(),
                    'attributes' => [],
                ],
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'premier_date',
                    'value' => $object->getPremierDate(),
                    'attributes' => [],
                ],
            ],
            'attributes' => [],
        ];

        $writer->write($meta);
    }
}
