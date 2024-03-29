<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Entity;

/**
 * Represents a "rich" sitemap entry.
 *
 * @see http://support.google.com/webmasters/bin/answer.py?hl=en&answer=2620865
 */
final class RichUrl extends Url
{
    /**
     * Alternate urls list, locale indexed.
     */
    private $alternateUrl = [];

    /**
     * Add an alternate url to the current one.
     *
     * If you have multiple language versions of a URL, each language page in
     * the set must use rel="alternate" hreflang="x" to identify all language
     * versions including itself. For example, if your site provides content
     * in French, English, and Spanish, the Spanish version must include a
     * rel="alternate" hreflang="x" link for itself in addition to links to the
     * French and English versions. Similarly the English and French versions
     * must each include the same references to the French, English, and
     * Spanish versions.
     *
     * @param string $locale The url's language (and optionnaly region. Ex: en, en-us).
     */
    public function addAlternateUrl(string $locale, string $url): void
    {
        $this->alternateUrl[$locale] = $url;
    }

    public function getAlternateUrls(): iterable
    {
        return $this->alternateUrl;
    }
}
