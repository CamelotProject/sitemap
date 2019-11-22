<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Fixtures\Element;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Camelot\Sitemap\Tests\Fixtures\Repository\SitemapMetaRepository")
 *
 * @internal
 */
class SitemapMeta
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** @ORM\Column(type="string", length=16) */
    private ?string $changeFrequency = null;

    /** @ORM\Column(type="float") */
    private ?float $priority = null;

    /** @ORM\Column(type="string", length=255) */
    private ?string $routeName = null;

    /** @ORM\Column(type="string", length=255) */
    private ?string $routeParam = null;

    public static function create(): self
    {
        return new self();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChangeFrequency(): ?string
    {
        return $this->changeFrequency;
    }

    public function setChangeFrequency(string $changeFrequency): self
    {
        $this->changeFrequency = $changeFrequency;

        return $this;
    }

    public function getPriority(): ?float
    {
        return $this->priority;
    }

    public function setPriority(float $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function setRouteName(string $routeName): self
    {
        $this->routeName = $routeName;

        return $this;
    }

    public function getRouteParam(): ?string
    {
        return $this->routeParam;
    }

    public function setRouteParam(string $routeParam): self
    {
        $this->routeParam = $routeParam;

        return $this;
    }
}
