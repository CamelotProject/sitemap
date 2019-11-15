<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Formatter;

use Camelot\Sitemap\Entity;

interface FormatterInterface
{
    public function getSitemapStart(): string;

    public function getSitemapEnd(): string;

    public function formatUrl(Entity\Url $url): string;
}
