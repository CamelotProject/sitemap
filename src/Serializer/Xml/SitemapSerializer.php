<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;

final class SitemapSerializer implements XmlSerializerInterface
{
    /** @param Element\Child\Sitemap $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            [
                'name' => Sitemap::XML_CLARK_NS . 'loc',
                'value' => $object->getLoc(),
                'attributes' => [],
            ],
        ];

        if ($object->getLastModified()) {
            $meta[] = [
                'name' => Sitemap::XML_CLARK_NS . 'lastmod',
                'value' => $object->getLastModified(),
                'attributes' => [],
            ];
        }

        $writer->write($meta);
    }
}
