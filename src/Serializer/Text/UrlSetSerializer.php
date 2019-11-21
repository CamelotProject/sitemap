<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Text;

use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\RootElementInterface;
use Camelot\Sitemap\Element\UrlSet;
use Camelot\Sitemap\Exception\SerializerException;
use function gettype;
use function sprintf;
use const PHP_EOL;

final class UrlSetSerializer implements TextSerializerInterface
{
    /** @param UrlSet $object */
    public function serialize(RootElementInterface $object): string
    {
        if (!$object instanceof UrlSet) {
            throw new SerializerException(sprintf('%s requires %s, %s passed', __METHOD__, UrlSet::class, gettype($object)));
        }

        $meta = '';
        /** @var Url $child */
        foreach ($object->getChildren() as $child) {
            $meta .= $child->getLoc() . PHP_EOL;
        }

        return $meta;
    }
}
