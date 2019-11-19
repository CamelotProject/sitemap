<?php

namespace Camelot\Sitemap\Tests\Formatter;

use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\SitemapIndex;
use Camelot\Sitemap\Formatter\FormatterInterface;
use Camelot\Sitemap\Formatter\IndexFormatterInterface;
use Camelot\Sitemap\Formatter\Spaceless as SpacelessFormatter;
use PHPUnit\Framework\TestCase;

class TestableFormatter implements FormatterInterface
{
    public function getSitemapStart(): string
    {
        return "\tjoe\n";
    }

    public function getSitemapEnd(): string
    {
        return "\tfoo\n";
    }

    public function formatUrl(Url $url): string
    {
        return sprintf("\t%s\n", $url->getLoc());
    }
}

class SpacelessFormatterTest extends TestCase
{
    public function testSitemapStart(): void
    {
        $formatter = new SpacelessFormatter(new TestableFormatter());
        $this->assertSame('joe', $formatter->getSitemapStart());
    }

    public function testSitemapEnd(): void
    {
        $formatter = new SpacelessFormatter(new TestableFormatter());
        $this->assertSame('foo', $formatter->getSitemapEnd());
    }

    public function testGetSitemapIndexStartWithSitemapFormatter(): void
    {
        $formatter = new SpacelessFormatter(new TestableFormatter());
        $this->assertSame('', $formatter->getSitemapIndexStart());
    }

    public function testGetSitemapIndexStartWithSitemapIndexFormatter(): void
    {
        $sitemapIndexFormatter = $this->createMock(IndexFormatterInterface::class);
        $sitemapIndexFormatter
            ->expects($this->once())
            ->method('getSitemapIndexStart')
            ->will($this->returnValue("\tsome value with spaces\n"));

        $formatter = new SpacelessFormatter($sitemapIndexFormatter);

        $this->assertSame('some value with spaces', $formatter->getSitemapIndexStart());
    }

    public function testGetSitemapIndexEndWithSitemapFormatter(): void
    {
        $formatter = new SpacelessFormatter(new TestableFormatter());
        $this->assertSame('', $formatter->getSitemapIndexEnd());
    }

    public function testGetSitemapIndexEndWithSitemapIndexFormatter(): void
    {
        $sitemapIndexFormatter = $this->createMock(IndexFormatterInterface::class);
        $sitemapIndexFormatter
            ->expects($this->once())
            ->method('getSitemapIndexEnd')
            ->willReturn("\tsome value with spaces\n");

        $formatter = new SpacelessFormatter($sitemapIndexFormatter);

        $this->assertSame('some value with spaces', $formatter->getSitemapIndexEnd());
    }

    public function testFormatSitemapIndexWithSitemapFormatter(): void
    {
        $formatter = new SpacelessFormatter(new TestableFormatter());
        $entry = new SitemapIndex('not relevant');

        $this->assertSame('', $formatter->formatSitemapIndex($entry));
    }

    public function testFormatSitemapIndexWithSitemapIndexFormatter(): void
    {
        $entry = new SitemapIndex('not relevant');

        $sitemapIndexFormatter = $this->createMock(IndexFormatterInterface::class);
        $sitemapIndexFormatter
            ->expects($this->once())
            ->method('formatSitemapIndex')
            ->with($entry)
            ->willReturn("\tsome url\n");

        $formatter = new SpacelessFormatter($sitemapIndexFormatter);

        $this->assertSame('some url', $formatter->formatSitemapIndex($entry));
    }

    public function testFormatUrl(): void
    {
        $formatter = new SpacelessFormatter(new TestableFormatter());

        $url = new Url('http://www.google.fr');

        $this->assertSame('http://www.google.fr', $formatter->formatUrl($url));
    }
}
