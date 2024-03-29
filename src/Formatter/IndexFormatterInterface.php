<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Formatter;

use Camelot\Sitemap\Entity;

interface IndexFormatterInterface extends FormatterInterface
{
    public function getSitemapIndexStart(): string;

    public function getSitemapIndexEnd(): string;

    public function formatSitemapIndex(Entity\SitemapIndexEntry $entry): string;
}
