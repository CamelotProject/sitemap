<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Exception\DomainException;
use Camelot\Sitemap\Util\Assert;
use DateTimeInterface;
use const PHP_INT_MAX;

/**
 * Represents a video in a sitemap entry.
 *
 * @see https://developers.google.com/webmasters/videosearch/sitemaps
 */
final class Video implements UrlExtensionInterface
{
    // Required attributes

    /**
     * A URL pointing to the video thumbnail image file. Images must be at
     * least 160x90 pixels and at most 1920x1080 pixels. We recommend images
     * in .jpg, .png, or. gif formats.
     */
    private string $thumbnailLoc;
    /** The title of the video. Maximum 100 characters. */
    private string $title;
    /** The description of the video. Maximum 2048 characters. */
    private string $description;

    // Optional attributes

    /**
     * You must specify at least one of playerLoc or contentLoc attributes.
     *
     * A URL pointing to the actual video media file. This file should be in
     * .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm, .flv,
     * or other video file format.
     */
    private ?string $contentLoc = null;
    /**
     * You must specify at least one of playerLoc or contentLoc.
     *
     * A URL pointing to a player for a specific video. Usually this is the
     * information in the src element of an <embed> tag and should not be the
     * same as the content of the <loc> tag.
     *
     * The optional attribute allow_embed specifies whether Google can embed
     * the video in search results. Allowed values are Yes or No.
     *
     * The optional attribute autoplay has a user-defined string (in the
     * example above, ap=1) that Google may append (if appropriate) to the
     * flashvars parameter to enable autoplay of the video.
     * For example: <embed src="http://www.example.com/videoplayer.swf?video=123" autoplay="ap=1"/>.
     *
     * Example player URL for Dailymotion: http://www.dailymotion.com/swf/x1o2g
     */
    private ?VideoPlayerLocation $playerLoc = null;
    /**
     * The duration of the video in seconds. Value must be between 0 and
     * 28800 (8 hours).
     */
    private ?int $duration = null;
    /**
     * The date after which the video will no longer be available. Don't
     * supply this information if your video does not expire.
     */
    private ?DateTimeInterface $expirationDate = null;
    /**
     * The rating of the video. Allowed values are float numbers in the range
     * 0.0 to 5.0.
     */
    private ?float $rating = null;

    /**
     * Use <video:content_segment_loc>; only in conjunction with <video:player_loc>.
     *
     * If you publish your video as a series of raw videos (for example, if
     * you submit a full movie as a continuous series of shorter clips),
     * you can use the <video:content_segment_loc> to supply SEs with
     * a series of URLs, in the order in which they should be concatenated
     * to recreate the video in its entirety. Each URL should point to a
     * .mpg, .mpeg, .mp4, .m4v, .mov, .wmv, .asf, .avi, .ra, .ram, .rm,
     * .flv, or other video file format. It should not point to any Flash
     * content.
     *
     * @var VideoContentSegmentLocation[]
     */
    private array $contentSegmentLocations = [];
    /** The number of times the video has been viewed. */
    private ?int $viewCount = null;
    /** The date the video was first published. */
    private ?DateTimeInterface $publicationDate = null;
    /** Tags associated with the video. */
    private array $tags = [];
    /**
     * The video's category. For example, cooking. The value should be a
     * string no longer than 256 characters.
     */
    private ?string $category = null;
    /** No if the video should be available only to users with SafeSearch turned off. */
    private ?bool $familyFriendly = null;
    /**
     * A space-delimited list of countries where the video may or may not be
     * played. Allowed values are country codes in ISO 3166 format.
     *
     * @see https://developers.google.com/webmasters/videosearch/countryrestrictions
     */
    private ?VideoRestriction $restriction = null;
    /** A link to the gallery (collection of videos) in which this video appears. */
    private ?VideoGalleryLocation $galleryLoc = null;
    /** @var VideoPrice[] */
    private array $prices = [];
    /** Indicates whether a subscription (either paid or free) is required to view the video. */
    private ?bool $requiresSubscription = null;
    /** The video uploader. */
    private ?VideoUploader $uploader = null;
    /** Encloses all information about a single TV video. */
    private ?VideoTvShow $tvShow = null;
    /**
     * A list of space-delimited platforms where the video may or may not be
     * played. Allowed values are web, mobile, and tv.
     *
     * @see https://developers.google.com/webmasters/videosearch/platformrestrictions
     */
    private ?VideoPlatform $platform = null;
    /** Indicates whether the video is a live stream. */
    private ?bool $live = null;
    /**
     * Unambiguous identifiers for the video within a given identification context.
     *
     * @var VideoId[]
     */
    private array $ids = [];

    public function __construct(string $title, string $description, string $thumbnailLoc)
    {
        Assert::stringLength($title, 1, 100, 'title');
        Assert::stringLength($description, 1, 2048, 'description');

        $this->title = $title;
        $this->description = $description;
        $this->thumbnailLoc = $thumbnailLoc;
    }

    public static function create(string $title, string $description, string $thumbnailLoc): self
    {
        return new self($title, $description, $thumbnailLoc);
    }

    public function getThumbnailLoc(): string
    {
        return $this->thumbnailLoc;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getContentLoc(): ?string
    {
        return $this->contentLoc;
    }

    public function setContentLoc(string $loc): self
    {
        $this->contentLoc = $loc;

        return $this;
    }

    public function getPlayerLoc(): ?VideoPlayerLocation
    {
        return $this->playerLoc;
    }

    public function setPlayerLoc(VideoPlayerLocation $playerLocation): self
    {
        $this->playerLoc = $playerLocation;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        Assert::range($duration, 0, 28800, 'duration');

        $this->duration = $duration;

        return $this;
    }

    public function getExpirationDate(): ?string
    {
        return $this->expirationDate ? $this->expirationDate->format(DateTimeInterface::W3C) : null;
    }

    public function setExpirationDate(DateTimeInterface $date): self
    {
        $this->expirationDate = $date;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        Assert::range($rating, 0, 5, 'rating');

        $this->rating = $rating;

        return $this;
    }

    public function addContentSegmentLocation(VideoContentSegmentLocation $contentSegmentLocation): self
    {
        $this->contentSegmentLocations[] = $contentSegmentLocation;

        return $this;
    }

    public function getContentSegmentLocations(): array
    {
        return $this->contentSegmentLocations;
    }

    public function getViewCount(): ?int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $count): self
    {
        Assert::range($count, 0, PHP_INT_MAX, 'view_count');

        $this->viewCount = $count;

        return $this;
    }

    public function getPublicationDate(): ?string
    {
        return $this->publicationDate ? $this->publicationDate->format(DateTimeInterface::W3C) : null;
    }

    public function setPublicationDate(DateTimeInterface $date): self
    {
        $this->publicationDate = $date;

        return $this;
    }

    public function isFamilyFriendly(): ?bool
    {
        return $this->familyFriendly;
    }

    public function setFamilyFriendly(bool $friendly): self
    {
        $this->familyFriendly = $friendly;

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $tags): self
    {
        if (\count($tags) > 32) {
            throw new DomainException('A maximum of 32 tags is allowed.');
        }

        $this->tags = $tags;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        Assert::stringLength($category, 1, 256, 'category');

        $this->category = $category;

        return $this;
    }

    public function getRestriction(): ?VideoRestriction
    {
        return $this->restriction;
    }

    public function setRestriction(VideoRestriction $restriction): self
    {
        $this->restriction = $restriction;

        return $this;
    }

    public function getGalleryLoc(): ?VideoGalleryLocation
    {
        return $this->galleryLoc;
    }

    public function setGalleryLoc(VideoGalleryLocation $galleryLocation): self
    {
        $this->galleryLoc = $galleryLocation;

        return $this;
    }

    public function isRequiresSubscription(): ?bool
    {
        return $this->requiresSubscription;
    }

    public function setRequiresSubscription(bool $requiresSubscription): self
    {
        $this->requiresSubscription = $requiresSubscription;

        return $this;
    }

    public function getUploader(): ?VideoUploader
    {
        return $this->uploader;
    }

    public function setUploader(VideoUploader $uploader): self
    {
        $this->uploader = $uploader;

        return $this;
    }

    public function getTvShow(): ?VideoTvShow
    {
        return $this->tvShow;
    }

    public function setTvShow(VideoTvShow $tvShow): self
    {
        $this->tvShow = $tvShow;

        return $this;
    }

    public function getPlatform(): ?VideoPlatform
    {
        return $this->platform;
    }

    public function setPlatform(VideoPlatform $platform): self
    {
        $this->platform = $platform;

        return $this;
    }

    public function addPrice(VideoPrice $price): self
    {
        $this->prices[] = $price;

        return $this;
    }

    public function getPrices(): array
    {
        return $this->prices;
    }

    public function isLive(): ?bool
    {
        return $this->live;
    }

    public function setLive(bool $live): self
    {
        $this->live = $live;

        return $this;
    }

    public function addId(VideoId $id): self
    {
        $this->ids[] = $id;

        return $this;
    }

    public function getIds(): array
    {
        return $this->ids;
    }
}
