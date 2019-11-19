<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\VideoPrice;
use Camelot\Sitemap\Exception\DomainException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\VideoPrice
 *
 * @internal
 */
final class VideoPriceTest extends TestCase
{
    public function testGetCurrency(): void
    {
        static::assertSame('EUR', $this->getVideoPrice()->getCurrency());
    }

    public function testGetValue(): void
    {
        static::assertSame(4.21, $this->getVideoPrice()->getValue());
    }

    public function testGetType(): void
    {
        static::assertSame(VideoPrice::TYPE_RENT, $this->getVideoPrice()->getType());
    }

    public function testGetResolution(): void
    {
        static::assertSame(VideoPrice::RESOLUTION_HIGH, $this->getVideoPrice()->getResolution());
    }

    public function testInvalidType(): void
    {
        $this->expectException(DomainException::class);

        new VideoPrice('EUR', 4.21, 'stereo', VideoPrice::RESOLUTION_HIGH);
    }

    public function testInvalidResolution(): void
    {
        $this->expectException(DomainException::class);

        new VideoPrice('EUR', 4.21, VideoPrice::TYPE_RENT, 'new years');
    }

    private function getVideoPrice(): VideoPrice
    {
        return new VideoPrice('EUR', 4.21, VideoPrice::TYPE_RENT, VideoPrice::RESOLUTION_HIGH);
    }
}
