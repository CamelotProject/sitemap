<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element;

use Camelot\Sitemap\Element\Child\ChildNodeInterface;

interface RootElementInterface
{
    public function getChildren(): iterable;

    public function setChildren(iterable $children): self;

    public function addChild(ChildNodeInterface $child): self;
}
