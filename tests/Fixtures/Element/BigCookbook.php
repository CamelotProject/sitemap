<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Fixtures\Element;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Camelot\Sitemap\Tests\Fixtures\Repository\BigCookbookRepository")
 *
 * @internal
 */
class BigCookbook
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /** @ORM\Column(type="string", length=512) */
    private ?string $title = null;

    /** @ORM\Column(type="integer") */
    private ?int $pages = null;

    /** @ORM\Column(type="string", length=255) */
    private ?string $cuisine = null;

    /** @ORM\Column(type="datetime") */
    private ?DateTimeInterface $updated = null;

    public static function create(): self
    {
        return new self();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function getCuisine(): ?string
    {
        return $this->cuisine;
    }

    public function setCuisine(string $cuisine): self
    {
        $this->cuisine = $cuisine;

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
}
