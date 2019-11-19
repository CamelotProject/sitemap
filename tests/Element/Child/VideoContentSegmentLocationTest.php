<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\VideoContentSegmentLocation;
use Camelot\Sitemap\Exception\DomainException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\VideoContentSegmentLocation
 *
 * @internal
 */
final class VideoContentSegmentLocationTest extends TestCase
{
    public function testGetLoc(): void
    {
        static::assertSame('https://sitemap.test', (new VideoContentSegmentLocation('https://sitemap.test', 21))->getLoc());
    }

    public function providerDuration(): iterable
    {
        yield [0];
        yield [60];
        yield [28800];
    }

    /**
     * @dataProvider providerDuration
     */
    public function testGetDuration(int $expected): void
    {
        static::assertSame($expected, (new VideoContentSegmentLocation('https://sitemap.test', $expected))->getDuration());
    }

    public function testInvalidDuration(): void
    {
        $this->expectException(DomainException::class);

        new VideoContentSegmentLocation('https://sitemap.test', 28801);
    }
}
