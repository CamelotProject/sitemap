<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Generator;

use Camelot\Sitemap\Element\Child\Sitemap;
use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\RootElementInterface;
use Camelot\Sitemap\Element\SitemapIndex;
use Camelot\Sitemap\Element\UrlSet;
use Camelot\Sitemap\Exception\GeneratorException;
use Camelot\Sitemap\Generator\XmlGenerator;
use Camelot\Sitemap\Target\StreamFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Generator\XmlGenerator
 *
 * @internal
 */
final class XmlGeneratorTest extends TestCase
{
    public function testGenerateEmpty(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1"/>
EOF;

        $target = (new StreamFactory())->createMemory();
        $generator = new XmlGenerator();
        $generator->generate(new UrlSet(), $target);
        $result = $target->read();

        static::assertXmlStringEqualsXmlString($expected, $result);
    }

    public function testGenerateUrlSet(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1"
        xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
    <url>
        <loc>https://sitemap.test/index.html</loc>
    </url>
    <url>
        <loc>https://sitemap.test/blog/index.html</loc>
    </url>
</urlset>
EOF;

        $url1 = new Url('https://sitemap.test/index.html');
        $url2 = new Url('https://sitemap.test/blog/index.html');
        $urlSet = new UrlSet([$url1, $url2]);
        $target = (new StreamFactory())->createMemory();
        $generator = new XmlGenerator();
        $generator->generate($urlSet, $target);
        $result = $target->read();

        static::assertXmlStringEqualsXmlString($expected, $result);
    }

    public function testGenerateSitemapIndex(): void
    {
        $expected = <<<'EOF'
<?xml version="1.0"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <sitemap>
        <loc>https://sitemap.test/sitemap-1.xml</loc>
    </sitemap>
</sitemapindex>
EOF;
        $url1 = new Url('https://sitemap.test/index.html');
        $url2 = new Url('https://sitemap.test/blog/index.html');
        $urlSet = new SitemapIndex([$url1, $url2]);

        $sitemap = new Sitemap('https://sitemap.test/sitemap-1.xml');
        $index = new SitemapIndex([$sitemap], [$urlSet]);

        $target = (new StreamFactory())->createMemory();
        $generator = new XmlGenerator();
        $generator->generate($index, $target);
        $result = $target->read();

        static::assertXmlStringEqualsXmlString($expected, $result);
    }

    public function testGenerateWithUnknownParentInterfaceClass(): void
    {
        $this->expectException(GeneratorException::class);
        $this->expectExceptionMessageMatches('#^Unknown .+RootElementInterface object, .+UrlSet .+SitemapIndex supported, .+ given#');

        $urlSet = $this->createMock(RootElementInterface::class);
        $target = (new StreamFactory())->createMemory();
        $generator = new XmlGenerator();
        $generator->generate($urlSet, $target);
    }

//    public function testGenerateWithMissingRootElementMap(): void
//    {
//        $this->expectException(GeneratorException::class);
//        $this->expectExceptionMessageMatches('#^Unknown .+RootElementInterface object, .+UrlSet .+SitemapIndex supported, .+ given#');
//
//        $urlSet = $this->createMock(RootElementInterface::class);
//        $target = (new StreamFactory())->createMemory();
//        $generator = new XmlGenerator();
//        $generator->generate($urlSet, $target);
//    }
}
