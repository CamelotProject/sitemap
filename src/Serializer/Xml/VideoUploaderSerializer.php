<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoUploader;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;

final class VideoUploaderSerializer implements XmlSerializerInterface
{
    /** @param VideoUploader $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'uploader',
            'value' => $object->getName(),
            'attributes' => [
                'info' => $object->getInfo(),
            ],
        ];

        $writer->write($meta);
    }
}
