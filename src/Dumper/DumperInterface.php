<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Dumper;

/**
 * The dumper takes care of the sitemap's persistence (file, compressed file,
 * memory).
 */
interface DumperInterface
{
    /**
     * Dump a string.
     *
     * @param string $string The string to dump.
     *
     * @return void|string The dumped content (if available/relevant) or nothing.
     */
    public function dump(string $string);
}
