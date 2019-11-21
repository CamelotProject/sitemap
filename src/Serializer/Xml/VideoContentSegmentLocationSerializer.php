<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoContentSegmentLocation;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;

final class VideoContentSegmentLocationSerializer implements XmlSerializerInterface
{
    /** @param VideoContentSegmentLocation $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'content_segment_loc',
            'value' => $object->getLoc(),
            'attributes' => [
                'duration' => (string) $object->getDuration(),
            ],
        ];

        $writer->write($meta);
    }
}
