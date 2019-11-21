<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\SitemapIndex;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;

final class SitemapIndexSerializer implements XmlSerializerInterface
{
    /** @param SitemapIndex $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [];
        foreach ($object->getChildren() as $sitemap) {
            $meta[] = [
                'name' => Sitemap::XML_CLARK_NS . 'sitemap',
                'value' => $sitemap,
                'attributes' => [],
            ];
        }

        $writer->write($meta);
    }
}
