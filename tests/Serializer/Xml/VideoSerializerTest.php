<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\Video;
use Camelot\Sitemap\Element\Child\VideoContentSegmentLocation;
use Camelot\Sitemap\Element\Child\VideoGalleryLocation;
use Camelot\Sitemap\Element\Child\VideoId;
use Camelot\Sitemap\Element\Child\VideoPlatform;
use Camelot\Sitemap\Element\Child\VideoPlayerLocation;
use Camelot\Sitemap\Element\Child\VideoPrice;
use Camelot\Sitemap\Element\Child\VideoRestriction;
use Camelot\Sitemap\Element\Child\VideoTvShow;
use Camelot\Sitemap\Element\Child\VideoUploader;
use Camelot\Sitemap\Serializer\Xml\VideoSerializer;
use Camelot\Sitemap\Sitemap;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use function array_merge;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\VideoSerializer
 *
 * @internal
 */
final class VideoSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    private const BASE_META = [
        [
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'thumbnail_loc',
            'value' => 'https://sitemap.test/thumbnail.png',
            'attributes' => [],
        ],
        [
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'title',
            'value' => 'Something about Mary',
            'attributes' => [],
        ],
        [
            'name' => Sitemap::VIDEO_XML_CLARK_NS . 'description',
            'value' => 'A movie',
            'attributes' => [],
        ],
    ];

    public function testSerialize(): void
    {
        $writer = $this->createWriterMock(self::BASE_META);
        VideoSerializer::serialize($writer, $this->getVideo());
    }

    public function testSerializeWithContentLoc(): void
    {
        $writer = $this->createWriterMock(array_merge(
            self::BASE_META,
            [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'content_loc',
                    'value' => 'https://sitemap.test/video.ogg',
                    'attributes' => [],
                ],
            ]
        ));
        VideoSerializer::serialize($writer, $this->getVideo()->setContentLoc('https://sitemap.test/video.ogg'));
    }

    public function testSerializeWithPlayerLoc(): void
    {
        $playerLocation = new VideoPlayerLocation('https://sitemap.test/player.ext');
        $writer = $this->createWriterMock(array_merge(self::BASE_META, [$playerLocation]));

        VideoSerializer::serialize($writer, $this->getVideo()->setPlayerLoc($playerLocation));
    }

    public function testSerializeWithDuration(): void
    {
        $writer = $this->createWriterMock(array_merge(
            self::BASE_META,
            [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'duration',
                    'value' => 2468,
                    'attributes' => [],
                ],
            ]
        ));
        VideoSerializer::serialize($writer, $this->getVideo()->setDuration(2468));
    }

    public function testSerializeWithExpirationDate(): void
    {
        $expirationDate = new DateTimeImmutable('2000-01-01');

        $writer = $this->createWriterMock(array_merge(
            self::BASE_META,
            [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'expiration_date',
                    'value' => $expirationDate->format(DateTimeInterface::W3C),
                    'attributes' => [],
                ],
            ]
        ));
        VideoSerializer::serialize($writer, $this->getVideo()->setExpirationDate($expirationDate));
    }

    public function testSerializeWithRating(): void
    {
        $writer = $this->createWriterMock(array_merge(
            self::BASE_META,
            [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'rating',
                    'value' => 2.4,
                    'attributes' => [],
                ],
            ]
        ));
        VideoSerializer::serialize($writer, $this->getVideo()->setRating(2.4));
    }

    public function testSerializeContentSegmentLocation(): void
    {
        $contentSegmentLocation = new VideoContentSegmentLocation('https://sitemap.test/video-1.ogg', 60);

        $writer = $this->createWriterMock(array_merge(self::BASE_META, [[$contentSegmentLocation]]));
        VideoSerializer::serialize($writer, $this->getVideo()->addContentSegmentLocation($contentSegmentLocation));
    }

    public function testSerializeWithViewCount(): void
    {
        $writer = $this->createWriterMock(array_merge(
            self::BASE_META,
            [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'view_count',
                    'value' => 42,
                    'attributes' => [],
                ],
            ]
        ));
        VideoSerializer::serialize($writer, $this->getVideo()->setViewCount(42));
    }

    public function testSerializeWithPublicationDate(): void
    {
        $publicationDate = new DateTimeImmutable();

        $writer = $this->createWriterMock(array_merge(
            self::BASE_META,
            [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'publication_date',
                    'value' => $publicationDate->format(DateTimeInterface::W3C),
                    'attributes' => [],
                ],
            ]
        ));
        VideoSerializer::serialize($writer, $this->getVideo()->setPublicationDate($publicationDate));
    }

    public function testSerializeWithTags(): void
    {
        $writer = $this->createWriterMock(array_merge(
            self::BASE_META,
            [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'tag',
                    'value' => 'comedy 90s',
                    'attributes' => [],
                ],
            ]
        ));
        VideoSerializer::serialize($writer, $this->getVideo()->setTags(['comedy', '90s']));
    }

    public function testSerializeWithCategory(): void
    {
        $writer = $this->createWriterMock(array_merge(
            self::BASE_META,
            [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'category',
                    'value' => 'Cheesy',
                    'attributes' => [],
                ],
            ]
        ));
        VideoSerializer::serialize($writer, $this->getVideo()->setCategory('Cheesy'));
    }

    public function providerFamilyFriendly(): iterable
    {
        yield ['yes', true];
        yield ['no', false];
    }

    /**
     * @dataProvider providerFamilyFriendly
     */
    public function testSerializeWithFamilyFriendly(string $expected, bool $familyFriendly): void
    {
        $writer = $this->createWriterMock(array_merge(
            self::BASE_META,
            [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'family_friendly',
                    'value' => $expected,
                    'attributes' => [],
                ],
            ]
        ));
        VideoSerializer::serialize($writer, $this->getVideo()->setFamilyFriendly($familyFriendly));
    }

    public function providerRestriction(): iterable
    {
        yield [VideoRestriction::ALLOW, ['nl', 'au']];
        yield [VideoRestriction::DENY, ['nl', 'au']];
    }

    /**
     * @dataProvider providerRestriction
     */
    public function testSerializeWithRestriction(string $relationship, ?array $countries): void
    {
        $restriction = new VideoRestriction($relationship, $countries);

        $writer = $this->createWriterMock(array_merge(self::BASE_META, [$restriction]));
        VideoSerializer::serialize($writer, $this->getVideo()->setRestriction($restriction));
    }

    public function providerGalleryLoc(): iterable
    {
        yield ['https://sitemap.test/gallery', null];
        yield ['https://sitemap.test/gallery', 'A gallery of our videos'];
    }

    /**
     * @dataProvider providerGalleryLoc
     */
    public function testSerializeWithGalleryLoc(string $loc, ?string $title): void
    {
        $galleryLocation = new VideoGalleryLocation($loc, $title);
        $writer = $this->createWriterMock(array_merge(self::BASE_META, [$galleryLocation]));

        VideoSerializer::serialize($writer, $this->getVideo()->setGalleryLoc($galleryLocation));
    }

    public function providerPrices(): iterable
    {
        yield ['EUR', 24.42, VideoPrice::TYPE_PURCHASE, VideoPrice::RESOLUTION_HIGH];
        yield ['USD', 42.24, VideoPrice::TYPE_RENT, VideoPrice::RESOLUTION_STANDARD];
    }

    /**
     * @dataProvider providerPrices
     */
    public function testSerializeWithPrices(string $currency, float $value, ?string $type, ?string $resolution): void
    {
        $price = new VideoPrice($currency, $value, $type, $resolution);

        $writer = $this->createWriterMock(array_merge(self::BASE_META, [[$price]]));
        VideoSerializer::serialize($writer, $this->getVideo()->addPrice($price));
    }

    public function providerRequiresSubscription(): iterable
    {
        yield ['yes', true];
        yield ['no', false];
    }

    /**
     * @dataProvider providerRequiresSubscription
     */
    public function testSerializeWithRequiresSubscription(string $expected, bool $requiresSubscription): void
    {
        $writer = $this->createWriterMock(array_merge(
            self::BASE_META,
            [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'requires_subscription',
                    'value' => $expected,
                    'attributes' => [],
                ],
            ]
        ));
        VideoSerializer::serialize($writer, $this->getVideo()->setRequiresSubscription($requiresSubscription));
    }

    public function testSerializeWithUploader(): void
    {
        $uploader = new VideoUploader('Mary');
        $writer = $this->createWriterMock(array_merge(self::BASE_META, [$uploader]));
        VideoSerializer::serialize($writer, $this->getVideo()->setUploader($uploader));
    }

    public function testSerializeWithTvShow(): void
    {
        $tvShow = new VideoTvShow('My TV Show', 'full', 'Season 2, Episode 1', 2, 1, new DateTimeImmutable('2020-01-01'));
        $writer = $this->createWriterMock(array_merge(self::BASE_META, [$tvShow]));
        VideoSerializer::serialize($writer, $this->getVideo()->setTvShow($tvShow));
    }

    public function providerPlatform(): iterable
    {
        yield [VideoPlatform::ALLOW, [VideoPlatform::TV, VideoPlatform::WEB]];
        yield [VideoPlatform::DENY, [VideoPlatform::MOBILE]];
    }

    /**
     * @dataProvider providerPlatform
     */
    public function testSerializeWithPlatform(string $relationship, ?array $countries): void
    {
        $platform = new VideoPlatform($relationship, $countries);

        $writer = $this->createWriterMock(array_merge(self::BASE_META, [$platform]));
        VideoSerializer::serialize($writer, $this->getVideo()->setPlatform($platform));
    }

    public function providerLive(): iterable
    {
        yield ['yes', true];
        yield ['no', false];
    }

    /**
     * @dataProvider providerLive
     */
    public function testSerializeWithLive(string $expected, bool $live): void
    {
        $writer = $this->createWriterMock(array_merge(
            self::BASE_META,
            [
                [
                    'name' => Sitemap::VIDEO_XML_CLARK_NS . 'live',
                    'value' => $expected,
                    'attributes' => [],
                ],
            ]
        ));
        VideoSerializer::serialize($writer, $this->getVideo()->setLive($live));
    }

    public function testSerializeWithId(): void
    {
        $id = new VideoId('0d2c6e71-9ac3-4ef4-a6c2-899562e19ddc', VideoId::TYPE_TMS_PROGRAM);
        $writer = $this->createWriterMock(array_merge(self::BASE_META, [[$id]]));
        VideoSerializer::serialize($writer, $this->getVideo()->addId($id));
    }

    private function getVideo(): Video
    {
        return new Video('Something about Mary', 'A movie', 'https://sitemap.test/thumbnail.png');
    }
}
