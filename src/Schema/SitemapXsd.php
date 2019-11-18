<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Schema;

final class SitemapXsd implements SchemaInterface
{
    use SchemaTrait;

    protected static function getPath(): string
    {
        return self::$schemaPath . 'sitemap.xsd';
    }
}
