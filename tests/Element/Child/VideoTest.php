<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

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
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DomainException;
use PHPUnit\Framework\TestCase;
use function array_pad;
use function str_repeat;

/**
 * @covers \Camelot\Sitemap\Element\Child\Video
 *
 * @internal
 */
final class VideoTest extends TestCase
{
    public function testGetTitle(): void
    {
        $expected = str_repeat('o', 100);
        $video = Video::create($expected, 'Description.', 'https://thumbnail.loc/img.jpg');

        static::assertSame($expected, $video->getTitle());
    }

    public function testTitleMaxLength(): void
    {
        $this->expectException(DomainException::class);

        new Video(str_repeat('o', 101), 'Description.', 'https://thumbnail.loc/img.jpg');
    }

    public function testThumbnailLoc(): void
    {
        $expected = 'https://thumbnail.loc/img.jpg';
        $video = Video::create('title', 'Description.', $expected);

        static::assertSame($expected, $video->getThumbnailLoc());
    }

    public function testDescription(): void
    {
        $expected = str_repeat('o', 2048);
        $video = Video::create('title', $expected, 'https://thumbnail.loc/img.jpg');

        static::assertSame($expected, $video->getDescription());
    }

    public function testDescriptionMaxLength(): void
    {
        $this->expectException(DomainException::class);

        new Video('title', str_repeat('o', 2048), 'https://thumbnail.loc/img.jpg');
        static::assertTrue(true);

        new Video('title', str_repeat('o', 2049), 'https://thumbnail.loc/img.jpg');
    }

    public function testContentLoc(): void
    {
        $expected = 'Right here!';
        $video = $this->getVideo();

        static::assertSame($expected, $video->setContentLoc($expected)->getContentLoc());
    }

    public function providerPlayerLoc(): iterable
    {
        yield ['https://sitemap.test', true];
        yield ['https://sitemap.test', false];
    }

    /**
     * @dataProvider providerPlayerLoc
     */
    public function testPlayerLoc(string $loc, bool $allowEmbed): void
    {
        $expected = new VideoPlayerLocation($loc, $allowEmbed);
        $video = $this->getVideo();

        static::assertSame($expected, $video->setPlayerLoc($expected)->getPlayerLoc());
    }

    public function testDuration(): void
    {
        $expected = 42;
        $video = $this->getVideo();

        static::assertSame($expected, $video->setDuration($expected)->getDuration());
    }

    public function invalidDurationProvider(): iterable
    {
        yield [-1];
        yield [28801];
    }

    /**
     * @dataProvider invalidDurationProvider
     */
    public function testInvalidDuration(int $duration): void
    {
        $this->expectException(DomainException::class);

        $video = $this->getVideo();
        $video->setDuration($duration);
    }

    /**
     * @dataProvider dateProvider
     */
    public function testExpirationDate(DateTimeInterface $date, string $expected): void
    {
        $video = $this->getVideo();
        $video->setExpirationDate($date);

        static::assertSame($video->getExpirationDate(), $expected);
    }

    public function testRating(): void
    {
        $video = $this->getVideo();

        static::assertSame(4.2, $video->setRating(4.2)->getRating());
    }

    public function invalidRatingProvider(): iterable
    {
        yield [-1];
        yield [6];
    }

    /**
     * @dataProvider invalidRatingProvider
     */
    public function testInvalidRating(float $rating): void
    {
        $this->expectException(DomainException::class);

        $this->getVideo()->setRating($rating);
    }

    public function testContentSegmentLocation(): void
    {
        $video = $this->getVideo();
        $location = new VideoContentSegmentLocation('https://sitemap.test/segment', 60);

        static::assertSame([$location], $video->addContentSegmentLocation($location)->getContentSegmentLocations());
    }


    public function testViewCount(): void
    {
        $video = $this->getVideo();

        static::assertSame(42, $video->setViewCount(42)->getViewCount());
    }

    public function testInvalidViewCount(): void
    {
        $this->expectException(DomainException::class);

        $this->getVideo()->setViewCount(-1);
    }

    /**
     * @dataProvider dateProvider
     */
    public function testPublicationDate(DateTimeInterface $date, string $expected): void
    {
        $video = $this->getVideo();
        $video->setPublicationDate($date);

        static::assertSame($video->getPublicationDate(), $expected);
    }

    public function providerFamilyFriendly(): iterable
    {
        yield [true];
        yield [false];
    }

    /**
     * @dataProvider providerFamilyFriendly
     */
    public function testFamilyFriendly(bool $familyFriendly): void
    {
        $video = $this->getVideo();

        static::assertSame($familyFriendly, $video->setFamilyFriendly($familyFriendly)->isFamilyFriendly());
    }

    public function testTags(): void
    {
        $expected = array_pad([], 32, 'tag');
        $video = $this->getVideo();

        static::assertSame($expected, $video->setTags($expected)->getTags());
    }

    public function testInvalidTagsCount(): void
    {
        $this->expectException(DomainException::class);

        $this->getVideo()->setTags(array_pad([], 33, 'tag'));
    }

    public function testCategory(): void
    {
        $expected = str_repeat('o', 256);
        $video = $this->getVideo();

        static::assertSame($expected, $video->setCategory($expected)->getCategory());
    }

    public function testCategoryMaxLength(): void
    {
        $this->expectException(DomainException::class);

        $video = $this->getVideo();
        $video->setCategory(str_repeat('o', 257));
    }

    public function providerRestriction(): iterable
    {
        yield [['fr', 'en'], VideoRestriction::ALLOW];
        yield [['fr', 'en'], VideoRestriction::DENY];
    }

    /**
     * @dataProvider providerRestriction
     */
    public function testRestriction(array $countries, string $relationship): void
    {
        $expected = new VideoRestriction($relationship, $countries);
        $video = $this->getVideo();

        static::assertSame($expected, $video->setRestriction($expected)->getRestriction());
    }

    public function testInvalidRestriction(): void
    {
        $this->expectException(DomainException::class);

        $this->getVideo()->setRestriction(new VideoRestriction('foo', ['fr', 'en']));
    }

    public function providerGalleryLoc(): iterable
    {
        yield ['https://sitemap.test/gallery', null];
        yield ['https://sitemap.test/gallery', 'Princess'];
    }

    /**
     * @dataProvider providerGalleryLoc
     */
    public function testGalleryLoc(string $loc, ?string $title): void
    {
        $expected = new VideoGalleryLocation($loc, $title);
        $video = $this->getVideo();

        static::assertSame($expected, $video->setGalleryLoc($expected)->getGalleryLoc());
    }

    public function providerRequiresSubscription(): iterable
    {
        yield [true];
        yield [false];
    }

    /**
     * @dataProvider providerRequiresSubscription
     */
    public function testRequiresSubscription(bool $requiresSubscription): void
    {
        $video = $this->getVideo();

        static::assertSame($requiresSubscription, $video->setRequiresSubscription($requiresSubscription)->isRequiresSubscription());
    }

    public function providerUploader(): iterable
    {
        yield ['load-up', null];
        yield ['load-up', 'done'];
    }

    /**
     * @dataProvider providerUploader
     */
    public function testUploader(string $uploader, ?string $info): void
    {
        $expected = new VideoUploader($uploader, $info);
        $video = $this->getVideo();

        static::assertSame($expected, $video->setUploader($expected)->getUploader());
    }

    public function testTvShow(): void
    {
        $video = $this->getVideo();
        $tvShow = new VideoTvShow('A TV show clip', VideoTvShow::CLIP);

        static::assertSame($tvShow, $video->setTvShow($tvShow)->getTvShow());
    }

    public function testPlatforms(): void
    {
        $expected = new VideoPlatform(VideoPlatform::DENY, [VideoPlatform::WEB]);
        $video = $this->getVideo();

        static::assertSame($expected, $video->setPlatform($expected)->getPlatform());
    }

    public function testPrice(): void
    {
        $expected = new VideoPrice('EUR', 1024);
        $video = $this->getVideo();

        static::assertSame([$expected], $video->addPrice($expected)->getPrices());
    }

    public function providerLive(): iterable
    {
        yield [true];
        yield [false];
    }

    /**
     * @dataProvider providerLive
     */
    public function testLive(bool $live): void
    {
        $video = $this->getVideo();

        static::assertSame($live, $video->setLive($live)->isLive());
    }

    public function testId(): void
    {
        $video = $this->getVideo();
        $id = new VideoId('abc-123', VideoId::TYPE_TMS_SERIES);

        static::assertSame([$id], $video->addId($id)->getIds());
    }

    public function dateProvider(): iterable
    {
        yield [new DateTimeImmutable('2012-12-20'), (new DateTimeImmutable('2012-12-20'))->format(DateTime::W3C)];
    }

    private function getVideo(): Video
    {
        return Video::create('title', 'Description', 'https://thumbnail.loc/img.jpg');
    }
}
