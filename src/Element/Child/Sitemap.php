<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Util\Assert;
use DateTimeInterface;

final class Sitemap implements ChildNodeInterface
{
    /**
     * URL of the page.
     * MUST begin with the protocol (as it will be added later) AND MUST
     * end with a trailing slash, if your web server requires it. This value
     * must be less than 2,048 characters.
     */
    private string $loc;

    /**
     * The date of last modification of the file.
     *
     * NOTE This tag is separate from the If-Modified-Since (304) header
     * the server can return, and search engines may use the information from
     * both sources differently.
     */
    private ?DateTimeInterface $lastModified;

    public function __construct(string $loc, ?DateTimeInterface $lastMod = null)
    {
        Assert::urlHasScheme($loc);
        Assert::stringLength($loc, 1, 2048, 'loc');

        $this->loc = $loc;
        $this->lastModified = $lastMod;
    }

    public static function create(string $loc, ?DateTimeInterface $lastMod = null): self
    {
        return new self($loc, $lastMod);
    }

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function getLastModified(): ?string
    {
        return $this->lastModified ? $this->lastModified->format(DateTimeInterface::W3C) : null;
    }

    public function setLastModified(?DateTimeInterface $lastModified): self
    {
        $this->lastModified = $lastModified;

        return $this;
    }
}
