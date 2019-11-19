<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Formatter;

use Camelot\Sitemap\Element;

interface FormatterInterface
{
    public function getSitemapStart(): string;

    public function getSitemapEnd(): string;

    public function formatUrl(Element\Child\Url $url): string;
}
