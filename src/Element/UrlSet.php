<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element;

use Camelot\Sitemap\Element\Child\ChildNodeInterface;
use Camelot\Sitemap\Element\Child\Url;
use function iter\chain;
use function iter\toIter;

final class UrlSet implements RootElementInterface
{
    /** @var Url[] */
    private iterable $urls;

    public function __construct(iterable $urls = [])
    {
        $this->urls = toIter($urls);
    }

    public function getChildren(): iterable
    {
        return $this->urls;
    }

    public function setChildren(iterable $children): RootElementInterface
    {
        $this->urls = toIter($children);

        return $this;
    }

    /** @param Url $child */
    public function addChild(ChildNodeInterface $child): RootElementInterface
    {
        $this->urls = chain($this->urls, [$child]);

        return $this;
    }
}
