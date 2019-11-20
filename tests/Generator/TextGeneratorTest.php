<?php

namespace Camelot\Sitemap\Tests\Generator;

use Camelot\Sitemap\Element\Child\Url;
use PHPUnit\Framework\TestCase;

class TextGeneratorTest extends TestCase
{
    public function testSitemapStart(): void
    {
        $formatter = new \Camelot\Sitemap\Generator\TextGenerator();
        $this->assertSame('', $formatter->getSitemapStart());
    }

    public function testSitemapEnd(): void
    {
        $formatter = new \Camelot\Sitemap\Generator\TextGenerator();
        $this->assertSame('', $formatter->getSitemapEnd());
    }

    public function testFormatUrl(): void
    {
        $formatter = new \Camelot\Sitemap\Generator\TextGenerator();

        $url = new Url('http://www.google.fr');

        $this->assertSame('http://www.google.fr' . "\n", $formatter->formatUrl($url));
    }
}
