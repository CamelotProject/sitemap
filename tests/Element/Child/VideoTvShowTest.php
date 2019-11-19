<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\VideoTvShow;
use Camelot\Sitemap\Exception\DomainException;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\VideoTvShow
 *
 * @internal
 */
final class VideoTvShowTest extends TestCase
{
    public function testGetShowTitle(): void
    {
        static::assertSame('My TV Show', $this->getVideoTvShow()->getShowTitle());
    }

    public function providerVideoType(): iterable
    {
        yield ['full'];
        yield ['preview'];
        yield ['clip'];
        yield ['interview'];
        yield ['news'];
        yield ['other'];
    }

    /**
     * @dataProvider providerVideoType
     */
    public function testGetVideoType(string $type): void
    {
        static::assertSame($type, $this->getVideoTvShow($type)->getVideoType());
    }

    public function testInvalidVideoType(): void
    {
        $this->expectException(DomainException::class);

        $this->getVideoTvShow('an-invalid-type-string')->getVideoType();
    }

    public function testGetEpisodeTitle(): void
    {
        static::assertSame('Season 2, Episode 1', $this->getVideoTvShow()->getEpisodeTitle());
    }

    public function providerSeasonOrEpisodeNumber(): iterable
    {
        yield [null];
        yield [1];
        yield [9999];
    }

    /**
     * @dataProvider providerSeasonOrEpisodeNumber
     */
    public function testGetSeasonNumber(?int $expected): void
    {
        static::assertSame($expected, $this->getVideoTvShow('full', $expected)->getSeasonNumber());
    }

    /**
     * @dataProvider providerSeasonOrEpisodeNumber
     */
    public function testGetEpisodeNumber(?int $expected): void
    {
        static::assertSame($expected, $this->getVideoTvShow('full', null, $expected)->getEpisodeNumber());
    }

    public function providerInvalidSeasonOrEpisodeNumber(): iterable
    {
        yield [0];
        yield [-1];
    }

    /**
     * @dataProvider providerInvalidSeasonOrEpisodeNumber
     */
    public function testInvalidEpisodeNumber(int $expected): void
    {
        $this->expectException(DomainException::class);

        $this->getVideoTvShow('full', null, $expected)->getEpisodeNumber();
    }

    /**
     * @dataProvider providerInvalidSeasonOrEpisodeNumber
     */
    public function testInvalidSeasonNumber(int $expected): void
    {
        $this->expectException(DomainException::class);

        $this->getVideoTvShow('full', $expected)->getSeasonNumber();
    }

    public function testGetPremierDate(): void
    {
        static::assertSame('2020-01-01T00:00:00+00:00', $this->getVideoTvShow()->getPremierDate());
    }

    private function getVideoTvShow(string $videoType = 'full', ?int $seasonNumber = 2, ?int $episodeNumber = 1): VideoTvShow
    {
        return new VideoTvShow('My TV Show', $videoType, "Season {$seasonNumber}, Episode {$episodeNumber}", $seasonNumber, $episodeNumber, new DateTimeImmutable('2020-01-01'));
    }
}
