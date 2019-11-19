<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\VideoGalleryLocation;
use Camelot\Sitemap\Exception\DomainException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\VideoGalleryLocation
 *
 * @internal
 */
final class VideoGalleryLocationTest extends TestCase
{
    public function testLoc(): void
    {
        static::assertSame('https://sitemap.test/video/gallery.html', (new VideoGalleryLocation('https://sitemap.test/video/gallery.html'))->getLoc());
    }

    public function testLocWithoutScheme(): void
    {
        $this->expectException(DomainException::class);

        new VideoGalleryLocation('//sitemap.test/video/gallery.html');
    }

    public function testTitle(): void
    {
        static::assertSame('My video gallery', (new VideoGalleryLocation('https://sitemap.test/video/gallery.html', 'My video gallery'))->getTitle());
    }
}
