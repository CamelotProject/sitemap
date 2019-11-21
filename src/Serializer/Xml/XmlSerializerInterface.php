<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Serializer\SerializerInterface;
use Sabre\Xml\Writer;

interface XmlSerializerInterface extends SerializerInterface
{
    public static function serialize(Writer $writer, object $object): void;
}
