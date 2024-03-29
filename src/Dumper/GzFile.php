<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Dumper;

/**
 * Dump the sitemap into a compressed file.
 *
 * @see File
 */
final class GzFile extends File
{
    public function __construct(string $filename)
    {
        parent::__construct('compress.zlib://' . $filename);
    }

    /**
     * {@inheritdoc}
     */
    public function changeFile(string $filename): FileDumperInterface
    {
        return parent::changeFile(str_replace('compress.zlib://', '', $filename));
    }
}
