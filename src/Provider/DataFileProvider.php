<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Provider;

use DomainException;
use IteratorAggregate;
use SplFileInfo;
use Symfony\Component\Yaml\Yaml;
use Traversable;
use function array_filter;
use function sprintf;
use function var_export;
use const PHP_EOL;

final class DataFileProvider implements IteratorAggregate
{
    use ProviderTrait;

    public function getIterator(): Traversable
    {
        self::assertFile($this->options);

        $options = $this->normalizeOptions($this->options);
        $urls = $this->getFileContents($options['file']);

        foreach ($urls as $url) {
            $options['route'] = $url;
            yield $this->getUrlEntity($options);
        }
    }

    private function getFileContents(string $fileName): array
    {
        $fileExtension = (new SplFileInfo($fileName))->getExtension();
        if (\in_array($fileExtension, ['yaml', 'yml'], true)) {
            return $this->processYamlFile($fileName);
        }
        if (\in_array($fileExtension, ['txt', 'conf'], true)) {
            return $this->processTextFile($fileName);
        }

        throw new DomainException(sprintf('Data file %s extension not supported, only .yaml, .yml, .txt, .conf', $fileName));
    }

    private function processTextFile(string $fileName): array
    {
        $data = file($fileName);
        $result = [];
        foreach ($data as $datum) {
            $result[] = trim($datum);
        }

        return array_filter($result);
    }

    private function processYamlFile(string $fileName): array
    {
        return Yaml::parseFile($fileName);
    }

    private static function assertFile(array $entry): void
    {
        if (!isset($entry['file'])) {
            throw new DomainException(sprintf('Provider parameter "file" is required to be set. Passed:%s%s', PHP_EOL, var_export($entry, true)));
        }
    }
}
