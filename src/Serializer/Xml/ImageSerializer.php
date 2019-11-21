<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\Child\Image;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;

final class ImageSerializer implements XmlSerializerInterface
{
    /** @param Image $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            [
                'name' => Sitemap::IMAGE_XML_CLARK_NS . 'loc',
                'value' => $object->getLoc(),
                'attributes' => [],
            ],
        ];

        if ($object->getCaption()) {
            $meta[] = [
                'name' => Sitemap::IMAGE_XML_CLARK_NS . 'caption',
                'value' => $object->getCaption(),
                'attributes' => [],
            ];
        }

        if ($object->getGeoLocation()) {
            $meta[] = [
                'name' => Sitemap::IMAGE_XML_CLARK_NS . 'geo_location',
                'value' => $object->getGeoLocation(),
                'attributes' => [],
            ];
        }

        if ($object->getTitle()) {
            $meta[] = [
                'name' => Sitemap::IMAGE_XML_CLARK_NS . 'title',
                'value' => $object->getTitle(),
                'attributes' => [],
            ];
        }

        if ($object->getLicense()) {
            $meta[] = [
                'name' => Sitemap::IMAGE_XML_CLARK_NS . 'license',
                'value' => $object->getLicense(),
                'attributes' => [],
            ];
        }

        $writer->write($meta);
    }
}
