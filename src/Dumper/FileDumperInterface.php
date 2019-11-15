<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Dumper;

/**
 * Dumps content into a file.
 */
interface FileDumperInterface extends DumperInterface
{
    public function getFilename(): string;

    /**
     * Returns a new dumper, exactly like the current but which dumps content
     * in the given file.
     *
     * @param string $filename The new file to dump content to.
     */
    public function changeFile(string $filename): FileDumperInterface;
}
