<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoPlatform;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;
use function implode;

final class VideoPlatformSerializer implements XmlSerializerInterface
{
    /** @param VideoPlatform $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'platform',
            'value' => implode(' ', $object->getPlatforms()),
            'attributes' => [
                'relationship' => $object->getRelationship(),
            ],
        ];

        $writer->write($meta);
    }
}
