<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Generator;

use Camelot\Sitemap\Element;

interface GeneratorInterface
{
    public function getSitemapStart(): string;

    public function getSitemapEnd(): string;

    public function formatUrl(Element\Child\Url $url): string;
}
