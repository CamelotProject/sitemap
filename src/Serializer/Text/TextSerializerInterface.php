<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Text;

use Camelot\Sitemap\Element\RootElementInterface;
use Camelot\Sitemap\Serializer\SerializerInterface;

interface TextSerializerInterface extends SerializerInterface
{
    public function serialize(RootElementInterface $object): string;
}
