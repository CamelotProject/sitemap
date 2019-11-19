<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\ChildInterface;
use Camelot\Sitemap\Element\Child\Sitemap;
use Camelot\Sitemap\Element\Child\Url;
use DateTimeImmutable;
use DateTimeInterface;

trait LocLastModifiedTestTrait
{
    public function providerOptions(): iterable
    {
        yield ['https://sitemap.test/', null];
        yield ['https://sitemap.test/', new DateTimeImmutable('2019-12-12 12:12:12')];
    }

    /**
     * @dataProvider providerOptions
     */
    public function testLoc(string $loc, ?DateTimeInterface $lastMod): void
    {
        static::assertSame($loc, $this->getTestSubject($loc, $lastMod)->getLoc());
    }

    /**
     * @dataProvider providerOptions
     */
    public function testLastModifiedViaConstructor(string $loc, ?DateTimeInterface $lastMod): void
    {
        $expected = $lastMod ? $lastMod->format(DateTimeInterface::W3C) : $lastMod;

        static::assertSame($expected, $this->getTestSubject($loc, $lastMod)->getLastModified());
    }

    /**
     * @dataProvider providerOptions
     */
    public function testLastModifiedViaSetter(string $loc, ?DateTimeInterface $lastMod): void
    {
        $expected = $lastMod ? $lastMod->format(DateTimeInterface::W3C) : $lastMod;
        /** @var Sitemap|Url $subject */
        $subject = $this->getTestSubject($loc)->setLastModified($lastMod);

        static::assertSame($expected, $subject->getLastModified());
    }

    abstract protected function getTestSubject(string $loc, ?DateTimeInterface $lastMod = null): ChildInterface;
}
