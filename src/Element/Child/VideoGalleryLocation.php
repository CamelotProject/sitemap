<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Util\Assert;

/**
 * A link to the gallery (collection of videos) in which this video appears.
 */
final class VideoGalleryLocation
{
    private string $loc;
    private ?string $title;

    public function __construct(string $loc, ?string $title = null)
    {
        Assert::urlHasScheme($loc);

        $this->loc = $loc;
        $this->title = $title;
    }

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
