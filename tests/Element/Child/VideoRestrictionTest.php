<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\VideoRestriction;
use Camelot\Sitemap\Exception\DomainException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\VideoRestriction
 *
 * @internal
 */
final class VideoRestrictionTest extends TestCase
{
    public function testGetRelationship(): void
    {
        static::assertSame(VideoRestriction::DENY, $this->getVideoRestriction()->getRelationship());
    }

    public function testGetCountries(): void
    {
        static::assertSame(['us'], $this->getVideoRestriction()->getCountries());
    }

    public function testInvalidRelationship(): void
    {
        $this->expectException(DomainException::class);

        new VideoRestriction('water', ['earth']);
    }

    private function getVideoRestriction(): VideoRestriction
    {
        return new VideoRestriction(VideoRestriction::DENY, ['us']);
    }
}
