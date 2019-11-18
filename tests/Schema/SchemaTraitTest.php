<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Schema;

use Camelot\Sitemap\Exception\SchemaException;
use Camelot\Sitemap\Schema\SchemaInterface;
use Camelot\Sitemap\Schema\SchemaTrait;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Schema\SchemaTrait
 *
 * @internal
 */
final class SchemaTraitTest extends TestCase
{
    public function testPath(): void
    {
        $xsd = $this->getSchemaInterface();

        static::assertSame(__FILE__, $xsd::path());
    }

    public function testRead(): void
    {
        $xsd = $this->getSchemaInterface();

        static::assertStringEqualsFile(__FILE__, $xsd::read());
    }

    public function testReadException(): void
    {
        $this->expectException(SchemaException::class);
        $this->expectExceptionMessageMatches('/Filename cannot be empty/');

        $xsd = $this->getSchemaInterface();
        $xsd::$filePath = '';

        $xsd::read();
    }

    private function getSchemaInterface(): SchemaInterface
    {
        return new class() implements SchemaInterface {
            use SchemaTrait;

            public static string $filePath = __FILE__;
        };
    }
}
