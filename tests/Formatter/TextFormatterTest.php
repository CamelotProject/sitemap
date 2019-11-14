<?php

namespace Camelot\Sitemap\Tests\Formatter;

use Camelot\Sitemap\Entity\Url;
use Camelot\Sitemap\Formatter;
use PHPUnit\Framework\TestCase;

class TextFormatterTest extends TestCase
{
    public function testSitemapStart(): void
    {
        $formatter = new Formatter\Text();
        $this->assertSame('', $formatter->getSitemapStart());
    }

    public function testSitemapEnd(): void
    {
        $formatter = new Formatter\Text();
        $this->assertSame('', $formatter->getSitemapEnd());
    }

    public function testFormatUrl(): void
    {
        $formatter = new Formatter\Text();

        $url = new Url('http://www.google.fr');

        $this->assertSame('http://www.google.fr' . "\n", $formatter->formatUrl($url));
    }
}
