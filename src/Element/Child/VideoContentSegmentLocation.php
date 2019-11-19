<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Util\Assert;

final class VideoContentSegmentLocation
{
    private string $loc;
    private int $duration;

    public function __construct(string $loc, int $duration)
    {
        Assert::range($duration, 0, 28800, 'content_segment_loc:duration');

        $this->loc = $loc;
        $this->duration = $duration;
    }

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }
}
