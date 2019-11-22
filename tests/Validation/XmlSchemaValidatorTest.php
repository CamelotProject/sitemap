<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Validation;

use Camelot\Sitemap\Exception\ValidationException;
use Camelot\Sitemap\Tests\TestCaseFilesystem;
use Camelot\Sitemap\Validation\XmlSchemaValidator;
use PHPUnit\Framework\TestCase;
use function file_get_contents;

/**
 * @covers \Camelot\Sitemap\Validation\XmlSchemaValidator
 *
 * @internal
 */
final class XmlSchemaValidatorTest extends TestCase
{
    public function providerValidXml(): iterable
    {
        yield 'Sitemap' => ['sitemaps.org/sitemap.xml'];
        yield 'Sitemap with image extension' => ['sitemaps.org/sitemap-image.xml'];
        yield 'Sitemap with video extension' => ['sitemaps.org/sitemap-video.xml'];
        yield 'Sitemap with XHTML link' => ['sitemaps.org/sitemap-xhtml.xml'];
        yield 'Generated reference sitemap' => ['sitemap.xml'];
        yield 'Generated reference sitemapindex' => ['indexed/sitemap.xml'];
    }

    /**
     * @dataProvider providerValidXml
     */
    public function testValidate(string $xmlFile): void
    {
        static::assertTrue(XmlSchemaValidator::validate(file_get_contents(TestCaseFilesystem::expectation($xmlFile))));
    }

    public function testValidateEmptyDocument(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessageMatches('/DOMDocument::loadXML\(\): Start tag expected/');

        $xml = '<?xml version="1.0" encoding="UTF-8"?>';

        XmlSchemaValidator::validate($xml);
    }

    public function testValidateNotUrlSetOrSitemapIndex(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessageMatches('/First child is not a .urlset. or .sitemap/');

        $xml = '<?xml version="1.0"?><feed xmlns="http://www.w3.org/2005/Atom" />';

        XmlSchemaValidator::validate($xml);
    }

    /**
     * @dataProvider providerValidXml
     */
    public function testValidateFile(string $xmlFile): void
    {
        static::assertTrue(XmlSchemaValidator::validateFile(TestCaseFilesystem::expectation($xmlFile)));
    }

    public function providerInvalidXml(): iterable
    {
        yield 'Sitemap' => ['invalid/sitemap.xml'];
        yield 'Sitemap with image extension' => ['invalid/sitemap-image.xml'];
        yield 'Sitemap with video extension' => ['invalid/sitemap-video.xml'];
        yield 'Sitemap with XHTML link' => ['invalid/sitemap-xhtml.xml'];
    }

    /**
     * @dataProvider providerInvalidXml
     */
    public function testValidateInvalid(string $xmlFile): void
    {
        $this->expectException(ValidationException::class);

        XmlSchemaValidator::validate(file_get_contents(TestCaseFilesystem::expectation($xmlFile)));
    }

    /**
     * @dataProvider providerInvalidXml
     */
    public function testValidateFileInvalid(string $xmlFile): void
    {
        $this->expectException(ValidationException::class);

        XmlSchemaValidator::validateFile(TestCaseFilesystem::expectation($xmlFile));
    }
}
