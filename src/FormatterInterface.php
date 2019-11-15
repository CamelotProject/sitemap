<?php

declare(strict_types=1);

namespace Camelot\Sitemap;

interface FormatterInterface
{
    public function getSitemapStart(): string;

    public function getSitemapEnd(): string;

    public function formatUrl(Entity\Url $url): string;
}
