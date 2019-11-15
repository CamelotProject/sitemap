<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Formatter;

use Camelot\Sitemap\Entity\Url;

/**
 * Sitemaps formatted using this class will contain only one URL per line and
 * no other information.
 *
 * @see http://www.sitemaps.org/protocol.html#otherformats
 */
final class Text implements FormatterInterface
{
    public function getSitemapStart(): string
    {
        return '';
    }

    public function getSitemapEnd(): string
    {
        return '';
    }

    public function formatUrl(Url $url): string
    {
        return $url->getLoc() . "\n";
    }
}
