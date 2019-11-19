<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\AlternateUrl;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\AlternateUrl
 *
 * @internal
 */
final class AlternateUrlTest extends TestCase
{
    public function testGetUrl(): void
    {
        static::assertSame('https://nl.sitemap.test', (new AlternateUrl('nl', 'https://nl.sitemap.test'))->getUrl());
    }

    public function testGetLocale(): void
    {
        static::assertSame('nl', (new AlternateUrl('nl', 'https://nl.sitemap.test'))->getLocale());
    }
}
