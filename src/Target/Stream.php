<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Target;

use Camelot\Sitemap\Exception\StreamException;
use Camelot\Thrower\Thrower;
use ErrorException;
use function fflush;
use function rewind;
use function sprintf;

final class Stream implements ReadableTargetInterface
{
    /** @var resource */
    private $handle;

    public function __construct(Dsn $dsn, string $mode = 'w')
    {
        try {
            $handle = Thrower::call('fopen', (string) $dsn, $mode);
        } catch (ErrorException $e) {
            throw new StreamException(sprintf('Unable to open "%s" %s', (string) $dsn, $e->getMessage()), $e->getCode(), $e);
        }
        $this->handle = $handle;
    }

    public function read(): string
    {
        if (rewind($this->handle) === false) {
            throw new StreamException('Failed to rewind the stream.'); // @codeCoverageIgnore
        }

        try {
            $string = Thrower::call('stream_get_contents', $this->handle);
        } catch (ErrorException $e) { // @codeCoverageIgnore
            throw new StreamException(sprintf('Unable to read from stream: %s', $e->getMessage()), $e->getCode(), $e); // @codeCoverageIgnore
        }

        return $string;
    }

    public function write(string $string): void
    {
        try {
            Thrower::call('fwrite', $this->handle, $string);
        } catch (ErrorException $e) { // @codeCoverageIgnore
            throw new StreamException(sprintf('Unable to write to stream: %s', $e->getMessage()), $e->getCode(), $e);  // @codeCoverageIgnore
        }
        if (fflush($this->handle) === false) { // @codeCoverageIgnore
            throw new StreamException('Failed to flush the stream.'); // @codeCoverageIgnore
        }
    }
}
