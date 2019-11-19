<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

/**
 * Represents an image in a sitemap entry.
 *
 * @see http://support.google.com/webmasters/bin/answer.py?hl=fr&answer=178636
 */
final class Image implements UrlExtensionInterface
{
    /** The URL of the image. This attribute is required. */
    private string $loc;
    /** The caption of the image. */
    private ?string $caption = null;
    /** The geographic location of the image. */
    private ?string $geoLocation = null;
    /** The title of the image. */
    private ?string $title = null;
    /** A URL to the license of the image. */
    private ?string $license = null;

    public function __construct(string $loc)
    {
        $this->loc = $loc;
    }

    public static function create(string $loc): self
    {
        return new self($loc);
    }

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function setCaption(?string $caption): self
    {
        $this->caption = $caption;

        return $this;
    }

    public function getCaption(): ?string
    {
        return $this->caption;
    }

    public function setGeoLocation(?string $geoLocation): self
    {
        $this->geoLocation = $geoLocation;

        return $this;
    }

    public function getGeoLocation(): ?string
    {
        return $this->geoLocation;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setLicense(?string $license): self
    {
        $this->license = $license;

        return $this;
    }

    public function getLicense(): ?string
    {
        return $this->license;
    }
}
