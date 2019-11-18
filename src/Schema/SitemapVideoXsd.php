<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Schema;

final class SitemapVideoXsd implements SchemaInterface
{
    use SchemaTrait;

    protected static function getPath(): string
    {
        return self::$schemaPath . 'sitemap-video.xsd';
    }
}
