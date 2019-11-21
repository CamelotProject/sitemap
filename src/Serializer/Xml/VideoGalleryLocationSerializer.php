<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoGalleryLocation;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;

final class VideoGalleryLocationSerializer implements XmlSerializerInterface
{
    /** @param VideoGalleryLocation $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'gallery_loc',
            'value' => $object->getLoc(),
            'attributes' => [],
        ];
        if ($object->getTitle()) {
            $meta['attributes'] = [
                'title' => $object->getTitle(),
            ];
        }

        $writer->write($meta);
    }
}
