<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Target;

use Camelot\Sitemap\Exception\StreamException;
use Camelot\Sitemap\Target\Dsn;
use Camelot\Sitemap\Target\Stream;
use Camelot\Sitemap\Tests\TestCaseFilesystem;
use PHPUnit\Framework\TestCase;
use function file_get_contents;

/**
 * @covers \Camelot\Sitemap\Target\Stream
 *
 * @internal
 */
final class StreamTest extends TestCase
{
    protected function tearDown(): void
    {
        TestCaseFilesystem::cleanup();
    }

    public function testWrite(): void
    {
        $expected = '<?xml version="1.0" encoding="UTF-8"?>';

        $dummyFile = TestCaseFilesystem::temp() . '/sitemap.xml';
        $stream = new Stream(new Dsn($dummyFile));
        $stream->write($expected);

        static::assertSame($expected, file_get_contents($dummyFile));
    }

    public function testRead(): void
    {
        $expected = '<?xml version="1.0" encoding="UTF-8"?>';

        $dummyFile = TestCaseFilesystem::temp() . '/sitemap.xml';
        $stream = new Stream(new Dsn($dummyFile), 'w+');
        $stream->write($expected);

        static::assertSame($expected, $stream->read());
    }

    public function testAnExceptionIsThrownForNonAccessibleFiles(): void
    {
        $this->expectException(StreamException::class);

        new Stream(new Dsn('/'));
    }
}
