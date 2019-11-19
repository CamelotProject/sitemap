<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Formatter;

use Camelot\Sitemap\Element\Child\AlternateUrl;
use Camelot\Sitemap\Element\Child\Url;

final class RichXml extends Xml
{
    public function getSitemapStart(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . '<urlset ' .
               'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" ' .
               'xmlns:xhtml="http://www.w3.org/1999/xhtml" ' .
               'xmlns:video="http://www.google.com/schemas/sitemap-video/1.1" ' .
               'xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";
    }

    protected function formatBody(Url $url): string
    {
        $buffer = parent::formatBody($url);

        if (!$url instanceof AlternateUrl) {
            return $buffer;
        }

        foreach ($url->getAlternateUrls() as $locale => $link) {
            $buffer .= "\t" . '<xhtml:link rel="alternate" hreflang="' . $this->escape($locale) . '" href="' . $this->escape($link) . '" />' . "\n";
        }

        return $buffer;
    }
}
