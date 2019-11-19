<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Util\Assert;
use DateTimeInterface;

/**
 * Encloses all information about a single TV video.
 */
final class VideoTvShow
{
    public const FULL = 'full';
    public const PREVIEW = 'preview';
    public const CLIP = 'clip';
    public const INTERVIEW = 'interview';
    public const NEWS = 'news';
    public const OTHER = 'other';

    /**
     * The title of the TV show. This should be the same for all
     * episodes from the same series.
     */
    private string $showTitle;

    /**
     * Describes the relationship of the video to the specified
     * TV show/episode.
     *
     * @var string one of 'full', 'preview', 'clip', 'interview', 'news', 'other'
     */
    private string $videoType;

    /**
     * The title of the episode—for example, "Flesh and Bone" is the
     * title of the Season 1, Episode 8 episode of Battlestar
     * Galactica. This tag is not necessary if the video is not
     * related to a specific episode (for example, if it's a trailer
     * for an entire series or season).
     */
    private ?string $episodeTitle;

    /** Only for shows with a per-season schedule. */
    private ?int $seasonNumber;

    /**
     * The episode number in number format. For TV shoes with a
     * per-season schedule, the first episode of each series should
     * be numbered 1.
     */
    private ?int $episodeNumber;

    /**
     * The date the content of the video was first broadcast, in
     * W3C format (for example, 2010-11-05).
     */
    private ?DateTimeInterface $premierDate;

    public function __construct(string $showTitle, string $videoType, ?string $episodeTitle = null, ?int $seasonNumber = null, ?int $episodeNumber = null, ?DateTimeInterface $premierDate = null)
    {
        Assert::oneOfOrNull($videoType, [self::FULL, self::PREVIEW, self::CLIP, self::INTERVIEW, self::NEWS, self::OTHER], 'video_type');
        if ($episodeNumber !== null) {
            Assert::min($episodeNumber, 1, 'episode_number');
        }
        if ($seasonNumber !== null) {
            Assert::min($seasonNumber, 1, 'season_number');
        }

        $this->showTitle = $showTitle;
        $this->videoType = $videoType;
        $this->episodeTitle = $episodeTitle;
        $this->seasonNumber = $seasonNumber;
        $this->episodeNumber = $episodeNumber;
        $this->premierDate = $premierDate;
    }

    public function getShowTitle(): string
    {
        return $this->showTitle;
    }

    public function getVideoType(): string
    {
        return $this->videoType;
    }

    public function getEpisodeTitle(): ?string
    {
        return $this->episodeTitle;
    }

    public function getSeasonNumber(): ?int
    {
        return $this->seasonNumber;
    }

    public function getEpisodeNumber(): ?int
    {
        return $this->episodeNumber;
    }

    public function getPremierDate(): ?string
    {
        return $this->premierDate ? $this->premierDate->format(DateTimeInterface::W3C) : null;
    }
}
