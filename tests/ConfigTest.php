<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests;

use Camelot\Sitemap\Config;
use Camelot\Sitemap\Sitemap;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Config
 *
 * @internal
 */
final class ConfigTest extends TestCase
{
    public function testGetSiteUrl(): void
    {
        static::assertSame('https://sitemap.camelot.test', $this->getConfig()->getHost());
    }

    public function providerFileName(): iterable
    {
        yield ['sitemap'];
        yield ['sitemap.xml'];
        yield ['sitemap.xml.gz'];
    }

    /**
     * @dataProvider providerFileName
     */
    public function testGetFileName(string $fileName): void
    {
        $config = new Config('https://sitemap.camelot.test', __DIR__, $fileName, Sitemap::XML, true, true, 42);

        static::assertSame('sitemap.xml.gz', $config->getFileName());
    }

    public function testGetFilePath(): void
    {
        static::assertSame(__DIR__ . '/sitemap.xml.gz', $this->getConfig()->getFilePath());
    }

    public function testGetFilePathWithSuffix(): void
    {
        static::assertSame(__DIR__ . '/sitemap-42.xml.gz', $this->getConfig()->getFilePath('42'));
    }

    public function testIsCompress(): void
    {
        static::assertTrue($this->getConfig()->isCompress());
    }

    public function testGetFormat(): void
    {
        static::assertSame(Sitemap::XML, $this->getConfig()->getFormat());
    }

    public function testIsIndexed(): void
    {
        static::assertTrue($this->getConfig()->isIndexed());
    }

    public function testGetLimit(): void
    {
        static::assertSame(42, $this->getConfig()->getLimit());
    }

    public function testGetContentType(): void
    {
        static::assertSame('application/xml', $this->getConfig()->getContentType());
    }

    private function getConfig(): \Camelot\Sitemap\Config
    {
        return new Config('https://sitemap.camelot.test', __DIR__, 'sitemap', Sitemap::XML, true, true, 42);
    }
}
