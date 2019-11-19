<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\ChildInterface;
use Camelot\Sitemap\Element\Child\Sitemap;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\Sitemap
 *
 * @internal
 */
final class SitemapTest extends TestCase
{
    use LocLastModifiedTestTrait;

    public function testCreate(): void
    {
        static::assertInstanceOf(Sitemap::class, Sitemap::create('https://sitemap.test/', null));
    }

    protected function getTestSubject(string $loc, ?DateTimeInterface $lastMod = null): ChildInterface
    {
        return Sitemap::create($loc, $lastMod);
    }

//    public function providerOptions(): iterable
//    {
//        yield ['https://sitemap.test/', null];
//        yield ['https://sitemap.test/', new DateTimeImmutable('2019-12-12 12:12:12')];
//    }
//
//    /**
//     * @dataProvider providerOptions
//     */
//    public function testLoc(string $loc, ?DateTimeInterface $lastMod): void
//    {
//        static::assertSame($loc, Sitemap::create($loc, $lastMod)->getLoc());
//    }
//
//    /**
//     * @dataProvider providerOptions
//     */
//    public function testLastModifiedViaConstructor(string $loc, ?DateTimeInterface $lastMod): void
//    {
//        $expected = $lastMod ? $lastMod->format(DateTimeInterface::W3C) : $lastMod;
//
//        static::assertSame($expected, Sitemap::create($loc, $lastMod)->getLastModified());
//    }
//
//    /**
//     * @dataProvider providerOptions
//     */
//    public function testLastModifiedViaSetter(string $loc, ?DateTimeInterface $lastMod): void
//    {
//        $expected = $lastMod ? $lastMod->format(DateTimeInterface::W3C) : $lastMod;
//
//        static::assertSame($expected, Sitemap::create($loc)->setLastModified($lastMod)->getLastModified());
//    }
}
