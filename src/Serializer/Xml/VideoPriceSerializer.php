<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoPrice;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;

final class VideoPriceSerializer implements XmlSerializerInterface
{
    /** @param VideoPrice $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'price',
            'value' => $object->getValue(),
            'attributes' => [
                'currency' => $object->getCurrency(),
                'type' => $object->getType(),
                'resolution' => $object->getResolution(),
            ],
        ];

        $writer->write($meta);
    }
}
