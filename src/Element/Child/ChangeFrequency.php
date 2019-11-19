<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Exception\DomainException;
use Camelot\Sitemap\Sitemap;
use function implode;
use function in_array;
use function sprintf;

final class ChangeFrequency
{
    private ?string $value = null;

    public function __construct(?string $changefreq)
    {
        $valid = [
            Sitemap::CHANGE_FREQ_NEVER, Sitemap::CHANGE_FREQ_ALWAYS,
            Sitemap::CHANGE_FREQ_HOURLY, Sitemap::CHANGE_FREQ_DAILY,
            Sitemap::CHANGE_FREQ_WEEKLY, Sitemap::CHANGE_FREQ_MONTHLY,
            Sitemap::CHANGE_FREQ_YEARLY,
        ];
        if ($changefreq && !in_array($changefreq, $valid, true)) {
            throw new DomainException(sprintf('The changefreq must be one of %s, %s given.', implode(', ', $valid), $changefreq));
        }
        $this->value = $changefreq;
    }

    public function get(): ?string
    {
        return $this->value;
    }

    public static function create(?string $changefreq): self
    {
        return new self($changefreq);
    }

    public static function always(): self
    {
        return new self(Sitemap::CHANGE_FREQ_ALWAYS);
    }

    public static function hourly(): self
    {
        return new self(Sitemap::CHANGE_FREQ_HOURLY);
    }

    public static function daily(): self
    {
        return new self(Sitemap::CHANGE_FREQ_DAILY);
    }

    public static function weekly(): self
    {
        return new self(Sitemap::CHANGE_FREQ_WEEKLY);
    }

    public static function monthly(): self
    {
        return new self(Sitemap::CHANGE_FREQ_MONTHLY);
    }

    public static function yearly(): self
    {
        return new self(Sitemap::CHANGE_FREQ_YEARLY);
    }

    public static function never(): self
    {
        return new self(Sitemap::CHANGE_FREQ_NEVER);
    }
}
