<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\Image;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\Image
 *
 * @internal
 */
final class ImageTest extends TestCase
{
    public function testGetLoc(): void
    {
        static::assertSame('http://image.test', $this->getImage()->getLoc());
    }

    public function testGetCaption(): void
    {
        static::assertSame('Something something darkside', $this->getImage()->setCaption('Something something darkside')->getCaption());
    }

    public function testGetGeoLocation(): void
    {
        static::assertSame('Earth', $this->getImage()->setGeoLocation('Earth')->getGeoLocation());
    }

    public function testGetTitle(): void
    {
        static::assertSame('FSM', $this->getImage()->setTitle('FSM')->getTitle());
    }

    public function testGetLicense(): void
    {
        static::assertSame('MIT', $this->getImage()->setLicense('MIT')->getLicense());
    }

    private function getImage(): Image
    {
        return Image::create('http://image.test');
    }
}
