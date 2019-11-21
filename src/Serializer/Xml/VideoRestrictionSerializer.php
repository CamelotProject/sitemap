<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\Child\VideoRestriction;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;
use function implode;

final class VideoRestrictionSerializer implements XmlSerializerInterface
{
    /** @param VideoRestriction $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'restriction',
            'value' => implode(' ', $object->getCountries()),
            'attributes' => [
                'relationship' => $object->getRelationship(),
            ],
        ];

        $writer->write($meta);
    }
}
