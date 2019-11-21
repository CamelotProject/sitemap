<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Generator;

use Camelot\Sitemap\Element\RootElementInterface;
use Camelot\Sitemap\Element\SitemapIndex;
use Camelot\Sitemap\Element\UrlSet;
use Camelot\Sitemap\Exception\GeneratorException;
use Camelot\Sitemap\Serializer\Text\SitemapIndexSerializer;
use Camelot\Sitemap\Serializer\Text\UrlSetSerializer;
use Camelot\Sitemap\Target\TargetInterface;
use function get_class;
use function sprintf;

final class TextGenerator implements GeneratorInterface
{
    public function generate(RootElementInterface $data, TargetInterface $target): void
    {
        if ($data instanceof UrlSet) {
            $serializer = new UrlSetSerializer();
        } elseif ($data instanceof SitemapIndex) {
            $serializer = new SitemapIndexSerializer();
        } else {
            throw new GeneratorException(sprintf('Unknown %s object, %s & %s supported, %s given.', RootElementInterface::class, UrlSet::class, SitemapIndex::class, get_class($data))); // @codeCoverageIgnore
        }
        $target->write($serializer->serialize($data));
    }
}
