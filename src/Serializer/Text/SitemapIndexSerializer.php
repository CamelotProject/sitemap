<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Text;

use Camelot\Sitemap\Element\Child\Sitemap;
use Camelot\Sitemap\Element\RootElementInterface;
use Camelot\Sitemap\Element\SitemapIndex;
use Camelot\Sitemap\Exception\SerializerException;
use function gettype;
use function sprintf;
use const PHP_EOL;

final class SitemapIndexSerializer implements TextSerializerInterface
{
    /** @param SitemapIndex $object */
    public function serialize(RootElementInterface $object): string
    {
        if (!$object instanceof SitemapIndex) {
            throw new SerializerException(sprintf('%s requires %s, %s passed', __METHOD__, SitemapIndex::class, gettype($object)));
        }

        $meta = '';
        /** @var Sitemap $child */
        foreach ($object->getChildren() as $child) {
            $meta .= $child->getLoc() . PHP_EOL;
        }

        return $meta;
    }
}
