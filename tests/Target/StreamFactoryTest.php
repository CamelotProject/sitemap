<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Target;

use Camelot\Sitemap\Exception\TargetException;
use Camelot\Sitemap\Target\Stream;
use Camelot\Sitemap\Target\StreamFactory;
use Camelot\Sitemap\Target\TargetInterface;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use function stream_get_meta_data;
use function sys_get_temp_dir;
use function tempnam;

/**
 * @covers \Camelot\Sitemap\Target\StreamFactory
 *
 * @internal
 */
final class StreamFactoryTest extends TestCase
{
    public function testCreate(): void
    {
        $this->expectException(TargetException::class);

        new StreamFactory(__CLASS__);
    }

    public function testCreateFile(): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'sitemap-');
        $target = (new StreamFactory())->createFile($tmpFile);

        static::assertInstanceOf(Stream::class, $target);
        static::assertStreamType('file://' . $tmpFile, $target);
    }

    public function testCreateFileGz(): void
    {
        $tmpFile = tempnam(sys_get_temp_dir(), 'sitemap-');
        $target = (new StreamFactory())->createFileGz($tmpFile);

        static::assertInstanceOf(Stream::class, $target);
        static::assertStreamType('compress.zlib://' . $tmpFile, $target);
    }

    public function testCreateMemory(): void
    {
        $target = (new StreamFactory())->createMemory();

        static::assertInstanceOf(Stream::class, $target);
        static::assertStreamType('php://memory', $target);
    }

    public function testCreateOutputBuffer(): void
    {
        $target = (new StreamFactory())->createOutputBuffer();

        static::assertInstanceOf(Stream::class, $target);
        static::assertStreamType('php://output', $target);
    }

    public function testCreateStdErr(): void
    {
        $target = (new StreamFactory())->createStdErr();

        static::assertInstanceOf(Stream::class, $target);
        static::assertStreamType('php://stderr', $target);
    }

    public function testCreateStdOut(): void
    {
        $target = (new StreamFactory())->createStdOut();

        static::assertInstanceOf(Stream::class, $target);
        static::assertStreamType('php://stdout', $target);
    }

    public function testCreateTemp(): void
    {
        $target = (new StreamFactory())->createTemp();

        static::assertInstanceOf(Stream::class, $target);
        static::assertStreamType('php://temp', $target);
    }

    private static function assertStreamType(string $expected, TargetInterface $target): void
    {
        $rc = new ReflectionClass($target);
        $rp = $rc->getProperty('handle');
        $rp->setAccessible(true);

        $handle = $rp->getValue($target);
        static::assertIsResource($handle, 'TargetInterface object does not contain a valid stream handle');

        $meta = stream_get_meta_data($handle);
        static::assertSame($expected, $meta['uri']);
    }
}
