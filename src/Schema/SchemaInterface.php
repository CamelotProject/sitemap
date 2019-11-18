<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Schema;

interface SchemaInterface
{
    public static function path(): string;

    public static function read(): string;
}
