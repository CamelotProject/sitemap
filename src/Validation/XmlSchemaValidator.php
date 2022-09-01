<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Validation;

use Camelot\Sitemap\Exception\ValidationException;
use Camelot\Sitemap\Schema\SitemapImageXsd;
use Camelot\Sitemap\Schema\SitemapIndexXsd;
use Camelot\Sitemap\Schema\SitemapVideoXsd;
use Camelot\Sitemap\Schema\SitemapXsd;
use Camelot\Sitemap\Schema\Xhtml1Xsd;
use Camelot\Sitemap\Target\Dsn;
use Camelot\Sitemap\Target\Stream;
use DOMDocument;
use Eclipxe\XmlSchemaValidator\Exceptions\XmlSchemaValidatorException;
use Eclipxe\XmlSchemaValidator\Schemas;
use Eclipxe\XmlSchemaValidator\SchemaValidator;
use Throwable;
use function pathinfo;
use function sprintf;
use const PATHINFO_EXTENSION;
use const PHP_EOL;

/**
 * @internal
 */
final class XmlSchemaValidator
{
    /** @codeCoverageIgnore */
    private function __construct(){}

    /**
     * Validates a documents XML schema against one or more local schema files.
     *
     * @throws ValidationException When validation fails
     */
    public static function validate(string $source): bool
    {
        $document = new DOMDocument();

        try {
            $document->loadXML($source);
        } catch (Throwable $e) {
            throw new ValidationException($e->getMessage(), (int) $e->getCode(), $e);
        }
        if (!$document->firstChild) {
            throw new ValidationException('XML document has no children');
        }
        $validator = new SchemaValidator($document);
        $schemas = $validator->buildSchemas();

        if ($document->firstChild->nodeName === 'urlset') {
            self::addUrlSetSchemas($schemas);
        } elseif ($document->firstChild->nodeName === 'sitemapindex') {
            self::addSitemapIndexSchemas($schemas);
        } else {
            throw new ValidationException(sprintf('First child is not a "urlset" or "sitemap", %s given.', $document->firstChild->nodeName));
        }

        try {
            $validator->validateWithSchemas($schemas);
        } catch (XmlSchemaValidatorException $e) {
            throw new ValidationException(self::unwindExceptions($e), (int) $e->getCode(), $e);
        }

        return true;
    }

    public static function validateFile(string $fileName): bool
    {
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $dsn = $ext === 'gz' ? Dsn::createFileGz($fileName) : Dsn::createFile($fileName);
        $target = new Stream($dsn, 'r');

        return self::validate($target->read());
    }

    private static function addUrlSetSchemas(Schemas $schemas): void
    {
        $schemas->create('http://www.sitemaps.org/schemas/sitemap/0.9', SitemapXsd::path());
        $schemas->create('http://www.w3.org/1999/xhtml', Xhtml1Xsd::path());
        $schemas->create('http://www.google.com/schemas/sitemap-image/1.1', SitemapImageXsd::path());
        $schemas->create('http://www.google.com/schemas/sitemap-video/1.1', SitemapVideoXsd::path());
    }

    private static function addSitemapIndexSchemas(Schemas $schemas): void
    {
        $schemas->create('http://www.sitemaps.org/schemas/sitemap/0.9', SitemapIndexXsd::path());
    }

    private static function unwindExceptions(XmlSchemaValidatorException $e): string
    {
        $message = $e->getMessage();
        $previous = $e->getPrevious();
        while ($previous) { // @codeCoverageIgnoreStart
            $message .= PHP_EOL . $previous->getMessage();
            $previous = $previous->getPrevious();
        } // @codeCoverageIgnoreEnd

        return $message;
    }
}
