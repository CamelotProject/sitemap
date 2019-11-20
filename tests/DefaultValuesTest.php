<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests;

use Camelot\Sitemap\DefaultValues;
use Camelot\Sitemap\Sitemap;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\DefaultValues
 *
 * @internal
 */
final class DefaultValuesTest extends TestCase
{
    public function testEmptyDefaultValuesCanBeCreated(): void
    {
        $values = DefaultValues::none();

        static::assertFalse($values->hasLastModified());
        static::assertFalse($values->hasPriority());
        static::assertFalse($values->hasChangeFreq());

        static::assertNull($values->getLastModified());
        static::assertNull($values->getPriority());
        static::assertNull($values->getChangeFrequency());
    }

    public function testDefaultValuesCanBeGiven(): void
    {
        $priority = 0.4;
        $changeFrequency = Sitemap::CHANGE_FREQ_ALWAYS;
        $lastModified = new DateTimeImmutable();

        $values = DefaultValues::create($priority, $changeFrequency, $lastModified);

        static::assertTrue($values->hasLastModified());
        static::assertTrue($values->hasPriority());
        static::assertTrue($values->hasChangeFreq());

        static::assertSame($lastModified, $values->getLastModified());
        static::assertSame($priority, $values->getPriority());
        static::assertSame($changeFrequency, $values->getChangeFrequency());
    }

    public function testToArray(): void
    {
        $priority = 0.4;
        $changeFrequency = Sitemap::CHANGE_FREQ_ALWAYS;
        $lastModified = new DateTimeImmutable();

        $values = \Camelot\Sitemap\DefaultValues::create($priority, $changeFrequency, $lastModified);

        $expected = [
            'changefreq' => $changeFrequency,
            'lastmod' => $lastModified,
            'priority' => $priority,
        ];

        static::assertSame($expected, $values->toArray());
    }
}
