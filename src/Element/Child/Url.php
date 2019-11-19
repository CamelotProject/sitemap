<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Util\Assert;
use DateTimeInterface;

final class Url implements ChildNodeInterface
{
    /**
     * URL of the page.
     *
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

    /**
     * How frequently the page is likely to change. This value provides general
     * information to search engines and may not correlate exactly to how often
     * they crawl the page.
     *
     * @see \Camelot\Sitemap\Sitemap
     */
    private ?ChangeFrequency $changeFrequency;

    /**
     * The priority of this URL relative to other URLs on your site. Valid
     * values range from 0.0 to 1.0. This value does not affect how your pages
     * are compared to pages on other sites—it only lets the search engines
     * know which pages you deem most important for the crawlers.
     *
     * The default priority of a page is 0.5 (if not set in the sitemap).
     */
    private ?float $priority;

    /**
     * Alternate urls list, locale indexed.
     *
     * If you have multiple language versions of a URL, each language page in
     * the set must use rel="alternate" hreflang="x" to identify all language
     * versions including itself. For example, if your site provides content
     * in French, English, and Spanish, the Spanish version must include a
     * rel="alternate" hreflang="x" link for itself in addition to links to the
     * French and English versions. Similarly the English and French versions
     * must each include the same references to the French, English, and
     * Spanish versions.
     *
     * @var AlternateUrl[]
     */
    private array $alternateUrl = [];

    /**
     * @see https://support.google.com/webmasters/answer/178636
     *
     * @var Image[]
     */
    private array $images = [];

    /**
     * @see https://support.google.com/webmasters/answer/80471
     *
     * @var Video[]
     */
    private array $videos = [];

    public function __construct(string $loc, ?DateTimeInterface $lastMod = null, ?ChangeFrequency $changeFreq = null, ?float $priority = null)
    {
        Assert::urlHasScheme($loc);
        Assert::stringLength($loc, 1, 2048, 'loc');
        Assert::range($priority, 0, 5, 'priority');

        $this->loc = $loc;
        $this->lastModified = $lastMod;
        $this->changeFrequency = $changeFreq;
        $this->priority = $priority;
    }

    public static function create(string $loc, ?DateTimeInterface $lastMod = null, ?ChangeFrequency $changeFreq = null, ?float $priority = null): self
    {
        return new self($loc, $lastMod, $changeFreq, $priority);
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

    public function getChangeFrequency(): ?string
    {
        return $this->changeFrequency ? $this->changeFrequency->get() : null;
    }

    public function setChangeFrequency(?ChangeFrequency $changeFrequency): self
    {
        $this->changeFrequency = $changeFrequency;

        return $this;
    }

    public function getPriority(): ?float
    {
        return $this->priority;
    }

    public function setPriority(?float $priority): self
    {
        Assert::range($priority, 0, 5, 'priority');

        $this->priority = $priority;

        return $this;
    }

    /** Add an alternate url to the current one. */
    public function addAlternateUrl(AlternateUrl $url): self
    {
        $this->alternateUrl[] = $url;

        return $this;
    }

    /** @return AlternateUrl[] */
    public function getAlternateUrls(): array
    {
        return $this->alternateUrl;
    }

    /** @return Image[] */
    public function getImages(): array
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        $this->images[] = $image;

        return $this;
    }

    /** @return Video[] */
    public function getVideos(): array
    {
        return $this->videos;
    }

    public function addVideo(Video $video): self
    {
        $this->videos[] = $video;

        return $this;
    }
}
