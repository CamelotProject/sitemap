<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Target;

use Camelot\Sitemap\Target\Dsn;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Target\Dsn
 *
 * @internal
 */
final class DsnTest extends TestCase
{
    public function testCreateFile(): void
    {
        static::assertSame('file://' . __FILE__, (string) Dsn::createFile(__FILE__));
    }

    public function testCreateFileGz(): void
    {
        static::assertSame('compress.zlib://' . __FILE__, (string) Dsn::createFileGz(__FILE__));
    }

    public function testCreateMemory(): void
    {
        static::assertSame('php://memory', (string) Dsn::createMemory());
    }

    public function testCreateOutputBuffer(): void
    {
        static::assertSame('php://output', (string) Dsn::createOutputBuffer());
    }

    public function testCreateStdErr(): void
    {
        static::assertSame('php://stderr', (string) Dsn::createStdErr());
    }

    public function testCreateStdOut(): void
    {
        static::assertSame('php://stdout', (string) Dsn::createStdOut());
    }

    public function testCreateTemp(): void
    {
        static::assertSame('php://temp', (string) Dsn::createTemp());
    }
}
