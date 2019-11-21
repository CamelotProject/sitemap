<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoPlayerLocation;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;

final class VideoPlayerLocationSerializer implements XmlSerializerInterface
{
    /** @param VideoPlayerLocation $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'player_loc',
            'value' => $object->getLoc(),
            'attributes' => [],
        ];
        if ($object->hasAllowEmbed()) {
            $meta['attributes'] = [
                'allow_embed' => $object->isAllowEmbed() ? 'yes' : 'no',
            ];
        }

        $writer->write($meta);
    }
}
