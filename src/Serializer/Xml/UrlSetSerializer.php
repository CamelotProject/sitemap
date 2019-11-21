<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\UrlSet;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;

final class UrlSetSerializer implements XmlSerializerInterface
{
    /** @param UrlSet $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [];
        foreach ($object->getChildren() as $url) {
            $meta[] = [
                'name' => Sitemap::XML_CLARK_NS . 'url',
                'value' => $url,
                'attributes' => [],
            ];
        }

        $writer->write($meta);
    }
}
