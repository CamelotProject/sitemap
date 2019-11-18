<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Schema;

final class Xhtml1Xsd implements SchemaInterface
{
    use SchemaTrait;

    protected static function getPath(): string
    {
        return self::$schemaPath . 'xhtml1-strict.xsd';
    }
}
