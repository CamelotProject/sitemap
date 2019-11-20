<?php

namespace Camelot\Sitemap\Tests;

use Camelot\Sitemap\DefaultValues;
use Camelot\Sitemap\Dumper;
use Camelot\Sitemap\Element\Child\ChangeFrequency;
use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Formatter;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

class SitemapTest extends TestCase
{
    public function testAddProvider(): void
    {
        $sitemap = new class($this->getDumper(), $this->getFormatter()) extends Sitemap {
            public function getProviders()
            {
                return $this->providers;
            }
        };
        $this->assertCount(0, $sitemap->getProviders());

        $sitemap->addProvider(new \ArrayIterator([]));
        $this->assertCount(1, $sitemap->getProviders());
    }

    public function testRelativeUrlsAreKeptIntact(): void
    {
        $dumper = new Dumper\Memory();
        $sitemap = new class($dumper, new \Camelot\Sitemap\Generator\TextGenerator()) extends Sitemap {
            public function testableAdd(Url $url): void
            {
                $this->add($url, DefaultValues::none());
            }
        };
        $url = new Url('/search');

        $sitemap->testableAdd($url);

        $this->assertSame('/search', $url->getLoc());
        $this->assertSame('/search' . "\n", $dumper->getBuffer());
    }

    public function testAddWithDefaultValues(): void
    {
        $formatter = $this->getFormatter();
        $sitemap = new Sitemap($this->getDumper(), $formatter);
        $defaultValues = DefaultValues::create(0.7, ChangeFrequency::ALWAYS);

        $formatter
            ->expects($this->once())
            ->method('formatUrl')
            ->with($this->callback(function(Url $url) {
                return $url->getPriority() === 0.7 && $url->getChangeFrequency() === ChangeFrequency::ALWAYS;
            }));

        $sitemap->addProvider(new \ArrayIterator([new Url('http://www.google.fr/search')]), $defaultValues);
        $sitemap->build();
    }

    public function testBuild(): void
    {
        $sitemap = new Sitemap(new Dumper\Memory(), new \Camelot\Sitemap\Generator\TextGenerator());
        $sitemap->addProvider(new \ArrayIterator([new Url('http://www.google.fr/search')]));

        $this->assertSame('http://www.google.fr/search' . "\n", $sitemap->build());
    }

    private function getDumper()
    {
        return $this->createMock(Dumper\DumperInterface::class);
    }

    private function getFormatter()
    {
        return $this->createMock(\Camelot\Sitemap\Generator\GeneratorInterface::class);
    }
}
