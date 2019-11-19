<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\VideoPlatform;
use Camelot\Sitemap\Exception\DomainException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\VideoPlatform
 *
 * @internal
 */
final class VideoPlatformTest extends TestCase
{
    public function providerParameters(): iterable
    {
        yield [VideoPlatform::ALLOW, [VideoPlatform::MOBILE]];
        yield [VideoPlatform::ALLOW, [VideoPlatform::TV]];
        yield [VideoPlatform::ALLOW, [VideoPlatform::WEB]];
        yield [VideoPlatform::ALLOW, [VideoPlatform::MOBILE, VideoPlatform::TV, VideoPlatform::WEB]];
        yield [VideoPlatform::DENY, [VideoPlatform::MOBILE]];
        yield [VideoPlatform::DENY, [VideoPlatform::TV]];
        yield [VideoPlatform::DENY, [VideoPlatform::WEB]];
        yield [VideoPlatform::DENY, [VideoPlatform::MOBILE, VideoPlatform::TV, VideoPlatform::WEB]];
    }

    /**
     * @dataProvider providerParameters
     */
    public function testParameters(string $relationship, array $platforms): void
    {
        $platform = new VideoPlatform($relationship, $platforms);

        static::assertSame($relationship, $platform->getRelationship());
        static::assertSame($platforms, $platform->getPlatforms());
    }

    public function testInvalidRelationship(): void
    {
        $this->expectException(DomainException::class);

        new VideoPlatform('Cousin Ed', [VideoPlatform::WEB]);
    }

    public function testInvalidPlatform(): void
    {
        $this->expectException(DomainException::class);

        new VideoPlatform(VideoPlatform::DENY, ['oil']);
    }
}
