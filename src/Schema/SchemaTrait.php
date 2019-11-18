<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Schema;

use Camelot\Sitemap\Exception\SchemaException;
use Camelot\Thrower\Thrower;
use ErrorException;
use ValueError;

trait SchemaTrait
{
    private static string $schemaPath = __DIR__ . '/../../schema/';

    public static function path(): string
    {
        return self::getPath();
    }

    public static function read(): string
    {
        try {
            return Thrower::call('file_get_contents', self::getPath());
        } catch (ErrorException|ValueError $e) {
            throw new SchemaException($e->getMessage(), $e->getCode(), $e);
        }
    }

    abstract protected static function getPath(): string;
}
