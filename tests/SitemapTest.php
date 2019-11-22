<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests;

use Camelot\Sitemap\Config;
use Camelot\Sitemap\Exception\ValidationException;
use Camelot\Sitemap\Sitemap;
use Camelot\Sitemap\Target\StreamFactory;
use Camelot\Sitemap\Tests\Fixtures\Provider\FunctionalTestProvider;
use Camelot\Sitemap\Validation\XmlSchemaValidator;
use PHPUnit\Framework\TestCase;
use function file_get_contents;
use function rtrim;
use function substr;
use const PHP_EOL;

/**
 * @covers \Camelot\Sitemap\Sitemap
 *
 * @internal
 */
final class SitemapTest extends TestCase
{
    private const IS_INDEXED = true;
    private const IS_COMPRESSED = true;
    private const NOT_INDEXED = false;
    private const NOT_COMPRESSED = false;

    protected function setUp(): void
    {
        TestCaseFilesystem::cleanup();
    }

    protected function tearDown(): void
    {
        TestCaseFilesystem::cleanup();
    }

    public function testGenerateXml(): void
    {
        $config = new Config('https://sitemap.test', TestCaseFilesystem::temp(), null, Sitemap::XML, self::NOT_COMPRESSED, self::NOT_INDEXED, 50000);
        $this->runGenerate($config);

        static::assertXmlFileEqualsXmlFile(TestCaseFilesystem::expectation('sitemap.xml'), $config->getFilePath());
        static::assertXmlMatchesXsd(file_get_contents($config->getFilePath()));
    }

    public function testGenerateIndexXml(): void
    {
        $config = new Config('https://sitemap.test', TestCaseFilesystem::temp(), null, Sitemap::XML, self::NOT_COMPRESSED, self::IS_INDEXED, 2);
        $this->runGenerate($config);

        static::assertXmlFileEqualsXmlFile(TestCaseFilesystem::expectation('indexed/sitemap.xml'), $config->getFilePath());
        static::assertXmlMatchesXsd(file_get_contents($config->getFilePath()));
        foreach (['1', '2', '3', '4'] as $index) {
            $baseName = rtrim($config->getFileName($index), '.gz');
            $expectedFile = TestCaseFilesystem::expectation('indexed/' . $baseName);
            $resultFile = $config->getFilePath($index);

            static::assertXmlFileEqualsXmlFile($expectedFile, $resultFile);
            static::assertXmlMatchesXsd(file_get_contents($resultFile));
        }
    }

    public function testGenerateXmlGz(): void
    {
        $config = new Config('https://sitemap.test', TestCaseFilesystem::temp(), null, Sitemap::XML, self::IS_COMPRESSED, self::NOT_INDEXED, 50000);
        $this->runGenerate($config);

        self::assertFileIsGzip($config->getFilePath());

        $factory = new StreamFactory();
        $expectedClean = rtrim('sitemap.xml', '.gz');
        $expected = $factory->createFileGz(TestCaseFilesystem::expectation($expectedClean), 'r')->read();
        $result = $factory->createFileGz($config->getFilePath(), 'r')->read();

        static::assertXmlStringEqualsXmlString($expected, $result);
        static::assertXmlMatchesXsd($result);
    }

    public function testGenerateIndexXmlGz(): void
    {
        $config = new Config('https://sitemap.test', TestCaseFilesystem::temp(), null, Sitemap::XML, self::IS_COMPRESSED, self::IS_INDEXED, 2);
        $this->runGenerate($config);

        self::assertFileIsGzip($config->getFilePath());

        $factory = new StreamFactory();
        $expected = $factory->createFileGz(TestCaseFilesystem::expectation('indexed/sitemap-gz.xml'), 'r')->read();
        $result = $factory->createFileGz($config->getFilePath(), 'r')->read();

        static::assertXmlStringEqualsXmlString($expected, $result);
        static::assertXmlMatchesXsd($result);

        foreach (['1', '2', '3', '4'] as $index) {
            $expectedPath = TestCaseFilesystem::expectation("indexed/sitemap-{$index}.xml");
            $resultPath = $config->getFilePath($index);

            self::assertFileIsGzip($resultPath);

            $expected = $factory->createFileGz($expectedPath, 'r')->read();
            $result = $factory->createFileGz($resultPath, 'r')->read();

            static::assertXmlStringEqualsXmlString($expected, $result);
            static::assertXmlMatchesXsd($result);
        }
    }

    public function testGenerateText(): void
    {
        $config = new Config('https://sitemap.test', TestCaseFilesystem::temp(), null, Sitemap::TXT, self::NOT_COMPRESSED, self::NOT_INDEXED, 50000);
        $this->runGenerate($config);

        static::assertFileEquals(TestCaseFilesystem::expectation('sitemap.txt'), $config->getFilePath());
    }

    public function testGenerateIndexedText(): void
    {
        $config = new Config('https://sitemap.test', TestCaseFilesystem::temp(), null, Sitemap::TXT, self::NOT_COMPRESSED, self::IS_INDEXED, 2);
        $this->runGenerate($config);

        static::assertFileEquals(TestCaseFilesystem::expectation('indexed/sitemap.txt'), $config->getFilePath());
    }

    public function testGenerateTextGz(): void
    {
        $config = new Config('https://sitemap.test', TestCaseFilesystem::temp(), null, Sitemap::TXT, self::IS_COMPRESSED, self::NOT_INDEXED, 50000);
        $this->runGenerate($config);

        self::assertFileIsGzip($config->getFilePath());

        $factory = new StreamFactory();
        $expected = $factory->createFileGz(TestCaseFilesystem::expectation('sitemap.txt'), 'r')->read();
        $result = $factory->createFileGz($config->getFilePath(), 'r')->read();

        static::assertSame($expected, $result);
    }

    public function testGenerateIndexTextGz(): void
    {
        $config = new Config('https://sitemap.test', TestCaseFilesystem::temp(), null, Sitemap::TXT, self::IS_COMPRESSED, self::IS_INDEXED, 2);
        $this->runGenerate($config);

        self::assertFileIsGzip($config->getFilePath());

        $factory = new StreamFactory();
        $expected = $factory->createFileGz(TestCaseFilesystem::expectation('indexed/sitemap-gz.txt'), 'r')->read();
        $result = $factory->createFileGz($config->getFilePath(), 'r')->read();

        static::assertSame($expected, $result);

        foreach (['1', '2', '3', '4'] as $index) {
            $expectedPath = TestCaseFilesystem::expectation("indexed/sitemap-{$index}.txt");
            $resultPath = $config->getFilePath($index);

            self::assertFileIsGzip($resultPath);

            $expected = $factory->createFileGz($expectedPath, 'r')->read();
            $result = $factory->createFileGz($resultPath, 'r')->read();
            static::assertSame($expected, $result);
        }
    }

    private function runGenerate(Config $config): void
    {
        $providers = new FunctionalTestProvider();
        $sitemap = new Sitemap();
        $sitemap->generate($providers, $config);
    }

    private static function assertFileIsGzip(string $filePath): void
    {
        $result = file_get_contents($filePath);
        if (mb_strpos($result, "\x1f" . "\x8b" . "\x08", 0, 'US-ASCII') !== 0) {
            static::fail('Generated file was not GZIP encoded!' . PHP_EOL . 'Header:' . PHP_EOL . substr($result, 0, 64) . ' ...');
        }
        static::assertTrue(true);
    }

    private static function assertXmlMatchesXsd(string $expected): void
    {
        try {
            XmlSchemaValidator::validate($expected);
            static::assertTrue(true);
        } catch (ValidationException $e) {
            static::fail('Generated XML failed validation: ' . $e->getMessage());
        }
    }
}
