<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Element\Child;

use Camelot\Sitemap\Util\Assert;

final class AlternateUrl
{
    private string $locale;
    /** The URL's language in ISO 639-1, and optionally a region in ISO 3166-1 Alpha 2, format. e.g. 'en', 'en-us'. */
    private string $url;

    public function __construct(string $locale, string $url)
    {
        Assert::urlHasScheme($url);

        $this->locale = $locale;
        $this->url = $url;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
