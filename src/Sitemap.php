<?php

declare(strict_types=1);

namespace Camelot\Sitemap;

use Camelot\Sitemap\Builder\Builder;
use Camelot\Sitemap\Element\SitemapIndex;
use Camelot\Sitemap\Generator\TextGenerator;
use Camelot\Sitemap\Generator\XmlGenerator;
use Camelot\Sitemap\Target\StreamFactory;

final class Sitemap
{
    public const XML = 'xml';
    public const TXT = 'txt';

    public const CHANGE_FREQ_ALWAYS = 'always';
    public const CHANGE_FREQ_HOURLY = 'hourly';
    public const CHANGE_FREQ_DAILY = 'daily';
    public const CHANGE_FREQ_WEEKLY = 'weekly';
    public const CHANGE_FREQ_MONTHLY = 'monthly';
    public const CHANGE_FREQ_YEARLY = 'yearly';
    public const CHANGE_FREQ_NEVER = 'never';

    public const XML_NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';
    public const XML_CLARK_NS = '{http://www.sitemaps.org/schemas/sitemap/0.9}';
    public const XHTML_NS = 'http://www.w3.org/1999/xhtml';
    public const XHTML_CLARK_NS = '{http://www.w3.org/1999/xhtml}';
    public const IMAGE_XML_NS = 'http://www.google.com/schemas/sitemap-image/1.1';
    public const IMAGE_XML_CLARK_NS = '{http://www.google.com/schemas/sitemap-image/1.1}';
    public const VIDEO_XML_NS = 'http://www.google.com/schemas/sitemap-video/1.1';
    public const VIDEO_XML_CLARK_NS = '{http://www.google.com/schemas/sitemap-video/1.1}';

    private StreamFactory $streamFactory;

    public function __construct()
    {
        $this->streamFactory = new StreamFactory();
    }

    public function generate(iterable $providers, Config $config): void
    {
        $targetPath = $config->getFilePath();
        $target = $config->isCompress() ? $this->streamFactory->createFileGz($targetPath) : $this->streamFactory->createFile($targetPath);
        $generator = $config->getFormat() === self::XML ? new XmlGenerator() : new TextGenerator();
        $builder = new Builder($providers, $config);

        $data = $builder->build();
        $generator->generate($data, $target);
        if (!$data instanceof SitemapIndex) {
            return;
        }

        $index = 0;
        foreach ($data->getGrandChildren() as $urlSet) {
            $targetPath = $config->getFilePath((string) ++$index);
            $target = $config->isCompress() ? $this->streamFactory->createFileGz($targetPath) : $this->streamFactory->createFile($targetPath);
            $generator->generate($urlSet, $target);
        }
    }
}
