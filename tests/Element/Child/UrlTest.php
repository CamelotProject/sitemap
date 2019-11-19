<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\AlternateUrl;
use Camelot\Sitemap\Element\Child\ChangeFrequency;
use Camelot\Sitemap\Element\Child\ChildInterface;
use Camelot\Sitemap\Element\Child\Image;
use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\Child\Video;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\Url
 *
 * @internal
 */
final class UrlTest extends TestCase
{
    use LocLastModifiedTestTrait;

    public function providerOptions(): iterable
    {
        $loc = 'https://sitemap.test/';

        yield 'Loc only' => [$loc, null, null, null];
        yield 'Loc & lastMod' => [$loc, new DateTimeImmutable('2000-01-02'), null, null];
        yield 'Loc & changeFreq' => [$loc, null, ChangeFrequency::hourly(), null];
        yield 'Loc & Priority' => [$loc, null, null, 0.3];
        yield 'All' => [$loc, new DateTimeImmutable('2000-01-02'), ChangeFrequency::hourly(), 0.3];
    }

    /**
     * @dataProvider providerOptions
     */
    public function testCreate(string $loc, ?DateTimeInterface $lastMod, ?ChangeFrequency $changeFreq, ?float $priority): void
    {
        static::assertInstanceOf(Url::class, Url::create($loc, $lastMod, $changeFreq, $priority));
    }

    public function testChangeFrequency(): void
    {
        static::assertSame('hourly', $this->getUrl()->setChangeFrequency(ChangeFrequency::hourly())->getChangeFrequency());
    }

    public function testPriority(): void
    {
        static::assertSame(0.3, $this->getUrl()->setPriority(0.3)->getPriority());
    }

    public function testAlternateUrls(): void
    {
        $expected = new AlternateUrl('nl', 'https://nl.sitemap.test');

        static::assertSame([$expected], $this->getUrl()->addAlternateUrl($expected)->getAlternateUrls());
    }

    public function testImages(): void
    {
        $expected = new Image('https://sitemap.test/image.png');

        static::assertSame([$expected], $this->getUrl()->addImage($expected)->getImages());
    }

    public function testVideos(): void
    {
        $expected = new Video('Title', 'Description', 'https://sitemap.test/thumbnail.png');

        static::assertSame([$expected], $this->getUrl()->addVideo($expected)->getVideos());
    }

    protected function getTestSubject(string $loc, ?DateTimeInterface $lastMod = null): ChildInterface
    {
        return Url::create($loc, $lastMod);
    }

    private function getUrl(): Url
    {
        return Url::create('https://sitemap.test/');
    }
}
