<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Target;

use function sprintf;

final class Dsn
{
    private string $dsn;

    /** @internal */
    public function __construct(string $dsn)
    {
        $this->dsn = $dsn;
    }

    public function __toString(): string
    {
        return $this->dsn;
    }

    public static function createFile(string $fileName): self
    {
        return new self(sprintf('file://%s', $fileName));
    }

    public static function createFileGz(string $fileName): self
    {
        return new self(sprintf('compress.zlib://%s', $fileName));
    }

    public static function createMemory(): self
    {
        return new self('php://memory');
    }

    public static function createOutputBuffer(): self
    {
        return new self('php://output');
    }

    public static function createStdErr(): self
    {
        return new self('php://stderr');
    }

    public static function createStdOut(): self
    {
        return new self('php://stdout');
    }

    public static function createTemp(): self
    {
        return new self('php://temp');
    }
}
