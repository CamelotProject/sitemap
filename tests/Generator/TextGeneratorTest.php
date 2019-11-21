<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Generator;

use Camelot\Sitemap\Element\Child\Sitemap;
use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\RootElementInterface;
use Camelot\Sitemap\Element\SitemapIndex;
use Camelot\Sitemap\Element\UrlSet;
use Camelot\Sitemap\Exception\GeneratorException;
use Camelot\Sitemap\Generator\TextGenerator;
use Camelot\Sitemap\Target\StreamFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Generator\TextGenerator
 *
 * @internal
 */
final class TextGeneratorTest extends TestCase
{
    public function testGenerateUrlSet(): void
    {
        $url1 = new Url('https://sitemap.test/index.html');
        $url2 = new Url('https://sitemap.test/blog/index.html');
        $urlSet = new UrlSet([$url1, $url2]);
        $target = (new StreamFactory())->createMemory();
        $generator = new TextGenerator();
        $generator->generate($urlSet, $target);
        $result = $target->read();

        static::assertStringContainsString('https://sitemap.test/index.html', $result);
        static::assertStringContainsString('https://sitemap.test/blog/index.html', $result);
    }

    public function testGenerateSitemapIndex(): void
    {
        $url1 = new Url('https://sitemap.test/index.html');
        $url2 = new Url('https://sitemap.test/blog/index.html');
        $urlSet = new SitemapIndex([$url1, $url2]);

        $sitemap = new Sitemap('https://sitemap.test/sitemap-1.xml');
        $index = new SitemapIndex([$sitemap], [$urlSet]);

        $target = (new StreamFactory())->createMemory();
        $generator = new TextGenerator();
        $generator->generate($index, $target);
        $result = $target->read();

        static::assertRegExp('#^https://sitemap.test/sitemap-1.xml\n#m', $result);
    }

    public function testGenerateWithUnknownParentInterfaceClass(): void
    {
        $this->expectException(GeneratorException::class);

        $urlSet = $this->createMock(RootElementInterface::class);
        $target = (new StreamFactory())->createMemory();
        $generator = new TextGenerator();
        $generator->generate($urlSet, $target);
    }
}
