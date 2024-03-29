<?php

namespace Camelot\Sitemap\Tests;

use Camelot\Sitemap\Dumper\FileDumperInterface;
use Camelot\Sitemap\Entity\Url;
use Camelot\Sitemap\Formatter\IndexFormatterInterface;
use Camelot\Sitemap\SitemapIndex;
use PHPUnit\Framework\TestCase;

class SitemapIndexTest extends TestCase
{
    const BASE_HOST = 'http://www.some-host.com/';

    public function testProvidersCanBeRegistered(): void
    {
        $sitemap = new SitemapIndex($this->getFileDumper(), $this->getFormatter(), self::BASE_HOST);
        $sitemap->addProvider(new \ArrayIterator([]));

        $this->assertTrue(true, 'It did NOT fail \o/');
    }

    public function testBuild(): void
    {
        $dumper = $this->getFileDumper();
        $formatter = $this->getFormatter();

        $sitemap = new SitemapIndex($dumper, $formatter, self::BASE_HOST, $sitemapMaxSize = 1);
        $sitemap->addProvider(new \ArrayIterator([
            new Url('foo'),
            new Url('bar'),
            new Url('baz'),
        ]));

        $formatter->expects($this->once())->method('getSitemapIndexStart');
        $formatter->expects($this->once())->method('getSitemapIndexEnd');
        $formatter->expects($this->exactly(3))->method('formatSitemapIndex');

        $dumper->method('getFilename')->willReturn('some-file-name');
        $dumper->expects($this->exactly(3))->method('changeFile');

        $sitemap->build();
    }

    private function getFileDumper()
    {
        return $this->createMock(FileDumperInterface::class);
    }

    private function getFormatter()
    {
        return $this->createMock(IndexFormatterInterface::class);
    }
}
