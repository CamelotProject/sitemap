<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\VideoPlayerLocation;
use Camelot\Sitemap\Exception\DomainException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\VideoPlayerLocation
 *
 * @internal
 */
final class VideoPlayerLocationTest extends TestCase
{
    public function testLoc(): void
    {
        static::assertSame('https://sitemap.test/video.php', (new VideoPlayerLocation('https://sitemap.test/video.php'))->getLoc());
    }

    public function testLocWithoutScheme(): void
    {
        $this->expectException(DomainException::class);

        new VideoPlayerLocation('//sitemap.test/video.php');
    }

    public function providerHasAllowEmbed(): iterable
    {
        yield [false, null];
        yield [true, false];
        yield [true, true];
    }

    /**
     * @dataProvider providerHasAllowEmbed
     */
    public function testHasAllowEmbed(bool $expected, ?bool $allowEmbed): void
    {
        static::assertSame($expected, (new VideoPlayerLocation('https://sitemap.test/video.php', $allowEmbed))->hasAllowEmbed());
    }

    public function providerIsAllowEmbed(): iterable
    {
        yield [false, false];
        yield [true, true];
    }

    /**
     * @dataProvider providerIsAllowEmbed
     */
    public function testIsAllowEmbed(bool $expected, ?bool $allowEmbed): void
    {
        static::assertSame($expected, (new VideoPlayerLocation('https://sitemap.test/video.php', $allowEmbed))->isAllowEmbed());
    }

    public function testIsAllowEmbedUnset(): void
    {
        $this->expectException(DomainException::class);

        (new VideoPlayerLocation('https://sitemap.test/video.php', null))->isAllowEmbed();
    }
}
