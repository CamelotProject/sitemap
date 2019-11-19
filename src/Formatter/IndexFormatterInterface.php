<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Formatter;

use Camelot\Sitemap\Element;

interface IndexFormatterInterface extends FormatterInterface
{
    public function getSitemapIndexStart(): string;

    public function getSitemapIndexEnd(): string;

    public function formatSitemapIndex(Element\SitemapIndex $entry): string;
}
