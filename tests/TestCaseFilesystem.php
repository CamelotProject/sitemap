<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests;

use Symfony\Component\Filesystem\Filesystem;

/**
 * @internal
 */
final class TestCaseFilesystem
{
    private const TMP = __DIR__ . '/.tmp';

    private function __construct() {}

    public static function temp(): string
    {
        $fs = new Filesystem();
        if (!$fs->exists(self::TMP)) {
            $fs->mkdir(self::TMP);
        }

        return self::TMP;
    }

    public static function cleanup(): void
    {
        $fs = new Filesystem();
        if ($fs->exists(self::TMP)) {
            $fs->remove(self::TMP);
        }
    }
}
