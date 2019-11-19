<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Formatter;

use Camelot\Sitemap\Element;

final class Spaceless implements IndexFormatterInterface
{
    private $formatter;

    public function __construct(FormatterInterface $formatter)
    {
        $this->formatter = $formatter;
    }

    public function getSitemapStart(): string
    {
        return $this->stripSpaces($this->formatter->getSitemapStart());
    }

    public function getSitemapEnd(): string
    {
        return $this->stripSpaces($this->formatter->getSitemapEnd());
    }

    public function formatUrl(Element\Child\Url $url): string
    {
        return $this->stripSpaces($this->formatter->formatUrl($url));
    }

    public function getSitemapIndexStart(): string
    {
        if (!$this->formatter instanceof IndexFormatterInterface) {
            return '';
        }

        return $this->stripSpaces($this->formatter->getSitemapIndexStart());
    }

    public function getSitemapIndexEnd(): string
    {
        if (!$this->formatter instanceof IndexFormatterInterface) {
            return '';
        }

        return $this->stripSpaces($this->formatter->getSitemapIndexEnd());
    }

    public function formatSitemapIndex(Element\SitemapIndex $entry): string
    {
        if (!$this->formatter instanceof IndexFormatterInterface) {
            return '';
        }

        return $this->stripSpaces($this->formatter->formatSitemapIndex($entry));
    }

    private function stripSpaces($string): string
    {
        return str_replace(["\t", "\r", "\n"], '', $string);
    }
}
