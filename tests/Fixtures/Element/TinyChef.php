<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Fixtures\Element;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Camelot\Sitemap\Tests\Fixtures\Repository\TinyChefRepository")
 *
 * @internal
 */
class TinyChef
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** @ORM\Column(type="string", length=255) */
    private ?string $name = null;

    /**
     * @ORM\OneToOne(targetEntity="Camelot\Sitemap\Tests\Fixtures\Element\BigCookbook", cascade={"persist", "remove"}, fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?BigCookbook $bigCookbook = null;

    /** @ORM\Column(type="datetime") */
    private ?DateTimeInterface $updated = null;

    /**
     * @ORM\ManyToOne(targetEntity="Camelot\Sitemap\Tests\Fixtures\Element\SitemapMeta", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?SitemapMeta $meta;

    public static function create(): self
    {
        return new self();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getBigCookbook(): ?BigCookbook
    {
        return $this->bigCookbook;
    }

    public function setBigCookbook(BigCookbook $bigCookbook): self
    {
        $this->bigCookbook = $bigCookbook;

        return $this;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getMeta(): ?SitemapMeta
    {
        return $this->meta;
    }

    public function setMeta(?SitemapMeta $meta): self
    {
        $this->meta = $meta;

        return $this;
    }
}
