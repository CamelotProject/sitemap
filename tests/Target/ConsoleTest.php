<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Target;

use Camelot\Sitemap\Target\Console;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * @covers \Camelot\Sitemap\Target\Console
 *
 * @internal
 */
final class ConsoleTest extends TestCase
{
    public function testWrite(): void
    {
        $expected = 'This is a string, the only string my friend.';

        $output = new BufferedOutput();
        $console = new Console(null, $output);
        $console->write($expected);

        static::assertSame($expected, $output->fetch());
    }
}
