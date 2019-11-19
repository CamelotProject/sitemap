<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use DateTimeInterface;

interface ChildNodeInterface extends ChildInterface
{
    public function getLoc(): string;

    public function getLastModified(): ?string;

    public function setLastModified(?DateTimeInterface $lastModified): self;
}
