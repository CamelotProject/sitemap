<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element;

use Camelot\Sitemap\Element\Child\ChildNodeInterface;
use Camelot\Sitemap\Element\Child\Sitemap;
use function iter\chain;
use function iter\toIter;

final class SitemapIndex implements RootElementInterface
{
    private iterable $sitemaps;
    private iterable $urlSets;

    public function __construct(iterable $sitemaps = [], iterable $urlSets = [])
    {
        $this->sitemaps = toIter($sitemaps);
        $this->urlSets = toIter($urlSets);
    }

    /** @return Sitemap[] */
    public function getChildren(): iterable
    {
        return $this->sitemaps;
    }

    public function setChildren(iterable $children): RootElementInterface
    {
        $this->sitemaps = toIter($children);

        return $this;
    }

    /** @param Sitemap $child */
    public function addChild(ChildNodeInterface $child): RootElementInterface
    {
        $this->sitemaps = chain($this->sitemaps, [$child]);

        return $this;
    }

    /** @return UrlSet[] */
    public function getGrandChildren(): iterable
    {
        return $this->urlSets;
    }

    public function setGrandChildren(iterable $urlSets): RootElementInterface
    {
        $this->urlSets = toIter($urlSets);

        return $this;
    }

    /** @param UrlSet $urlSet */
    public function addGrandChild(RootElementInterface $urlSet): RootElementInterface
    {
        $this->urlSets = chain($this->urlSets, [$urlSet]);

        return $this;
    }
}
