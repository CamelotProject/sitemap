<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\ChangeFrequency;
use Camelot\Sitemap\Exception\DomainException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\ChangeFrequency
 *
 * @internal
 */
final class ChangeFrequencyTest extends TestCase
{
    public function testAlways(): void
    {
        static::assertSame('always', ChangeFrequency::always()->get());
    }

    public function testHourly(): void
    {
        static::assertSame('hourly', ChangeFrequency::hourly()->get());
    }

    public function testDaily(): void
    {
        static::assertSame('daily', ChangeFrequency::daily()->get());
    }

    public function testWeekly(): void
    {
        static::assertSame('weekly', ChangeFrequency::weekly()->get());
    }

    public function testMonthly(): void
    {
        static::assertSame('monthly', ChangeFrequency::monthly()->get());
    }

    public function testYearly(): void
    {
        static::assertSame('yearly', ChangeFrequency::yearly()->get());
    }

    public function testNever(): void
    {
        static::assertSame('never', ChangeFrequency::never()->get());
    }

    public function testInvalid(): void
    {
        $this->expectException(DomainException::class);

        ChangeFrequency::create('sandman');
    }
}
