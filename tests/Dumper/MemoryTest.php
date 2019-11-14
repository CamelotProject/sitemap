<?php

namespace Camelot\Sitemap\Tests\Dumper;

use Camelot\Sitemap\Dumper\Memory;
use PHPUnit\Framework\TestCase;

class MemoryTest extends TestCase
{
    public function testDumper(): void
    {
        $dumper = new Memory();
        $this->assertSame('foo', $dumper->dump('foo'));
        $this->assertSame('foo', $dumper->getBuffer());
        $this->assertSame('foo-bar', $dumper->dump('-bar'));
        $this->assertSame('foo-bar', $dumper->getBuffer());
    }
}
