<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element\Child;

use Camelot\Sitemap\Element\Child\VideoId;
use Camelot\Sitemap\Exception\DomainException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Element\Child\VideoId
 *
 * @internal
 */
final class VideoIdTest extends TestCase
{
    public function testGetValue(): void
    {
        static::assertSame('A value', (new VideoId('A value', VideoId::TYPE_TMS_SERIES))->getValue());
    }

    public function providerTypes(): iterable
    {
        yield [VideoId::TYPE_TMS_SERIES];
        yield [VideoId::TYPE_TMS_PROGRAM];
        yield [VideoId::TYPE_ROVI_SERIES];
        yield [VideoId::TYPE_ROVI_PROGRAM];
        yield [VideoId::TYPE_FREEBASE];
        yield [VideoId::TYPE_URL];
    }

    /**
     * @dataProvider providerTypes
     */
    public function testGetType(string $expected): void
    {
        static::assertSame($expected, (new VideoId('A value', $expected))->getType());
    }

    public function testTypeInvalid(): void
    {
        $this->expectException(DomainException::class);

        new VideoId('A value', 'hoe-dan');
    }
}
