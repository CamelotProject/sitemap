<?php

declare(strict_types=1);

namespace Camelot\Sitemap;

use Camelot\Sitemap\Dumper\DumperInterface;
use Camelot\Sitemap\Entity\Url;
use Camelot\Sitemap\Formatter\FormatterInterface;
use Camelot\Sitemap\Provider\DefaultValues;

/**
 * Sitemap generator.
 *
 * It will use a set of providers to build the sitemap.
 * The dumper takes care of the sitemap's persistence (file, compressed file,
 * memory) and the formatter formats it.
 *
 * The whole process tries to be as memory-efficient as possible, that's why URLs
 * are not stored but dumped immediately.
 */
class Sitemap
{
    /**
     * @var \SplObjectStorage
     */
    protected $providers;

    /**
     * @var DumperInterface
     */
    private $dumper;

    /**
     * @var FormatterInterface
     */
    private $formatter;

    public function __construct(DumperInterface $dumper, FormatterInterface $formatter)
    {
        $this->dumper = $dumper;
        $this->formatter = $formatter;

        $this->providers = new \SplObjectStorage();
    }

    /**
     * @param \Traversable $provider A set of iterable Url objects.
     * @param DefaultValues $defaultValues Default values that will be used for Url entries.
     */
    public function addProvider(\Traversable $provider, DefaultValues $defaultValues = null): void
    {
        $this->providers->attach($provider, $defaultValues ?: DefaultValues::none());
    }

    /**
     * @return string|null The sitemap's content if available.
     */
    public function build(): ?string
    {
        $this->dumper->dump($this->formatter->getSitemapStart());

        foreach ($this->providers as $provider) {
            /** @var DefaultValues $defaultValues */
            $defaultValues = $this->providers[$provider];

            foreach ($provider as $entry) {
                $this->add($entry, $defaultValues);
            }
        }

        return $this->dumper->dump($this->formatter->getSitemapEnd());
    }

    protected function add(Url $url, DefaultValues $defaultValues): void
    {
        if (!$url->getPriority() && $defaultValues->hasPriority()) {
            $url->setPriority($defaultValues->getPriority());
        }

        if (!$url->getChangeFreq() && $defaultValues->hasChangeFreq()) {
            $url->setChangeFreq($defaultValues->getChangeFreq());
        }

        $this->dumper->dump($this->formatter->formatUrl($url));
    }
}
