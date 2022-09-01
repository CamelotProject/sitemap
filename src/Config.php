<?php

declare(strict_types=1);

namespace Camelot\Sitemap;

use Webmozart\Assert\Assert;
use function pathinfo;
use function sprintf;
use const DIRECTORY_SEPARATOR;
use const PATHINFO_EXTENSION;
use const PATHINFO_FILENAME;

final class Config
{
    private ?string $host;
    private string $fileName;
    private string $fileBasePath;
    private string $format;
    private bool $compress;
    private bool $indexed;
    private int $limit;

    public function __construct(?string $host, string $fileBasePath, ?string $fileName = null, ?string $format = null, bool $compress = false, bool $indexed = false, int $limit = 50000)
    {
        $this->host = $host;
        $this->fileBasePath = $fileBasePath;
        $this->format = $format ?: Sitemap::XML;
        $this->compress = $compress;
        $this->indexed = $indexed;
        $this->limit = $limit ?: 50000;
        $this->fileName = $this->resolveFileName($fileName ?: 'sitemap');

        Assert::range($this->limit, 0, 50000, 'Sitemap limit must be a positive integer, and can not be greater than 50,000. Given %s');
        Assert::oneOf($this->format, [Sitemap::XML, Sitemap::TXT], 'Invalid $format value %s passed to ' . __METHOD__ . '. Only %s are valid.');
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getFileName(string $suffix = null): string
    {
        return sprintf($this->fileName, $suffix ? "-{$suffix}" : '');
    }

    public function getFilePath(string $suffix = null): string
    {
        return $this->fileBasePath . DIRECTORY_SEPARATOR . $this->getFileName($suffix);
    }

    public function getContentType(): string
    {
        return 'application/xml';
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function isCompress(): bool
    {
        return $this->compress;
    }

    public function isIndexed(): bool
    {
        return $this->indexed;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    private function resolveFileName(string $fileName): string
    {
        $baseName = pathinfo($fileName, PATHINFO_FILENAME);
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        if ($ext === 'gz') {
            $baseName = pathinfo($baseName, PATHINFO_FILENAME);
            $ext = pathinfo($baseName, PATHINFO_EXTENSION);
        }
        if ($ext !== Sitemap::XML && $ext !== Sitemap::TXT) {
            $ext = $this->format;
        }
        if ($this->compress) {
            $ext .= '.gz';
        }

        return "{$baseName}%s.{$ext}";
    }
}
