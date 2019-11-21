<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoId;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;

final class VideoIdSerializer implements XmlSerializerInterface
{
    /** @param VideoId $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'id',
            'value' => $object->getValue(),
            'attributes' => [
                'type' => $object->getType(),
            ],
        ];

        $writer->write($meta);
    }
}
