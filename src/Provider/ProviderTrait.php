<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Provider;

use Camelot\Sitemap\DefaultValues;
use Camelot\Sitemap\Element\Child\ChangeFrequency;
use Camelot\Sitemap\Element\Child\Url;
use DateTimeImmutable;
use DateTimeInterface;
use Traversable;

/**
 * Trait containing common methods used by providers.
 */
trait ProviderTrait
{
    private array $options;
    private DefaultValues $defaultValues;

    /**
     * @param array $options Options used by the provider. This trait's methods use:
     * [
     *     [
     *         'route' => string,
     *         'lastmod' => string,
     *         'priority' => float,
     *         'changefreq' => string,
     *     ]
     * ]
     */
    public function __construct(array $options, DefaultValues $defaultValues = null)
    {
        $this->options = $options;
        $this->defaultValues = $defaultValues ?: DefaultValues::none();
    }

    public function getIterator(): Traversable
    {
        foreach ($this->options as $options) {
            yield $this->getUrlEntity($this->normalizeOptions($options));
        }
    }

    protected function getUrlEntity(array $entry): Url
    {
        return $this->setUrlParameters(new Url($entry['route']), $entry);
    }

    protected function setUrlParameters(Url $url, array $entry): Url
    {
        $url->setChangeFrequency($entry['changefreq'] ? ChangeFrequency::create($entry['changefreq']) : null);
        $url->setLastModified($entry['lastmod'] ? new DateTimeImmutable($entry['lastmod']) : null);
        $url->setPriority($entry['priority']);

        return $url;
    }

    private function normalizeOptions(array $options): array
    {
        $options['route'] = $options['route'] ?? null;
        $options['changefreq'] = $options['changefreq'] ?? $this->defaultValues->getChangeFrequency();
        $options['lastmod'] = $options['lastmod'] ?? ($this->defaultValues->getLastModified() ? $this->defaultValues->getLastModified()->format(DateTimeInterface::W3C) : null);
        $options['priority'] = $options['priority'] ?? $this->defaultValues->getPriority();

        return $options;
    }
}
