<?php

namespace Camelot\Sitemap\Tests\Element;

use Camelot\Sitemap\Element\SitemapIndex;
use PHPUnit\Framework\TestCase;

class SitemapIndexTest extends TestCase
{
    public function testLocMaxLength(): void
    {
        $this->expectException(\DomainException::class);

        new SitemapIndex('http://google.fr/?q=' . str_repeat('o', 2048));
    }

    public function testConstructionWithASingleArgument(): void
    {
        $entry = new SitemapIndex('http://google.fr/');

        $this->assertSame('http://google.fr/', $entry->getLoc());
        $this->assertNull($entry->getLastmod());
    }

    public function testConstructionWithAllTheArguments(): void
    {
        $entry = new SitemapIndex('http://google.fr/', \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2016-02-28 14:34:25', new \DateTimeZone('Europe/Paris')));

        $this->assertSame('http://google.fr/', $entry->getLoc());
        $this->assertSame('2016-02-28T14:34:25+01:00', $entry->getLastmod());
    }
}
