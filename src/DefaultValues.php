<?php

declare(strict_types=1);

namespace Camelot\Sitemap;

use DateTimeInterface;

final class DefaultValues
{
    private ?float $priority;
    private ?string $changeFrequency;
    private ?DateTimeInterface $lastModified;

    /** @codeCoverageIgnore  */
    private function __construct(?float $priority = null, ?string $changeFrequency = null, ?DateTimeInterface $lastModified = null)
    {
        $this->priority = $priority;
        $this->changeFrequency = $changeFrequency;
        $this->lastModified = $lastModified;
    }

    public static function create(?float $priority, ?string $changeFrequency, ?DateTimeInterface $lastModified = null): self
    {
        return new self($priority, $changeFrequency, $lastModified);
    }

    public static function none(): self
    {
        return new self();
    }

    public function hasLastModified(): bool
    {
        return $this->lastModified !== null;
    }

    public function getLastModified(): ?DateTimeInterface
    {
        return $this->lastModified;
    }

    public function hasPriority(): bool
    {
        return $this->priority !== null;
    }

    public function getPriority(): ?float
    {
        return $this->priority;
    }

    public function hasChangeFreq(): bool
    {
        return $this->changeFrequency !== null;
    }

    public function getChangeFrequency(): ?string
    {
        return $this->changeFrequency;
    }

    public function toArray(): array
    {
        return [
            'changefreq' => $this->changeFrequency,
            'lastmod' => $this->lastModified,
            'priority' => $this->priority,
        ];
    }
}
