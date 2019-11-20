<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Target;

use Camelot\Sitemap\Exception\TargetException;
use function is_a;
use function sprintf;

final class StreamFactory
{
    private string $streamClass;

    public function __construct(string $streamClass = Stream::class)
    {
        if (!is_a($streamClass, TargetInterface::class, true)) {
            throw new TargetException(sprintf('%s must implement %s', $streamClass, TargetInterface::class));
        }
        $this->streamClass = $streamClass;
    }

    public function create(Dsn $dsn, string $mode = 'w'): TargetInterface
    {
        return new $this->streamClass($dsn, $mode);
    }

    public function createFile(string $target, string $mode = 'w'): TargetInterface
    {
        return $this->create(Dsn::createFile($target), $mode);
    }

    public function createFileGz(string $target, string $mode = 'w'): TargetInterface
    {
        return $this->create(Dsn::createFileGz($target), $mode);
    }

    public function createMemory(): TargetInterface
    {
        return $this->create(Dsn::createMemory());
    }

    public function createOutputBuffer(): TargetInterface
    {
        return $this->create(Dsn::createOutputBuffer());
    }

    public function createStdErr(): TargetInterface
    {
        return $this->create(Dsn::createStdErr());
    }

    public function createStdOut(): TargetInterface
    {
        return $this->create(Dsn::createStdOut());
    }

    public function createTemp(): TargetInterface
    {
        return $this->create(Dsn::createTemp());
    }
}
