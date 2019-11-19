<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Exception\DomainException;
use Camelot\Sitemap\Util\Assert;
use function sprintf;

/**
 * A URL pointing to a player for a specific video.
 */
final class VideoPlayerLocation
{
    private string $loc;
    private ?bool $allowEmbed;

    public function __construct(string $loc, ?bool $allowEmbed = null)
    {
        Assert::urlHasScheme($loc);

        $this->loc = $loc;
        $this->allowEmbed = $allowEmbed;
    }

    public function getLoc(): string
    {
        return $this->loc;
    }

    public function hasAllowEmbed(): bool
    {
        return $this->allowEmbed !== null;
    }

    public function isAllowEmbed(): bool
    {
        if ($this->allowEmbed === null) {
            throw new DomainException(sprintf('%s allowEmbed parameter is null. You should check hasAllowEmbed() before calling isAllowEmbed()', __CLASS__));
        }

        return $this->allowEmbed;
    }
}
