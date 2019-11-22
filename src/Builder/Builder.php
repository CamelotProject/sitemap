<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Builder;

use Camelot\Sitemap\Config;
use Camelot\Sitemap\DefaultValues;
use Camelot\Sitemap\Element\Child\ChangeFrequency;
use Camelot\Sitemap\Element\Child\ChildNodeInterface;
use Camelot\Sitemap\Element\Child\Sitemap;
use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\RootElementInterface;
use Camelot\Sitemap\Element\SitemapIndex;
use Camelot\Sitemap\Element\UrlSet;
use Traversable;
use function iter\chain;
use function iter\chunk;
use function sprintf;

final class Builder
{
    private iterable $providers;
    private DefaultValues $defaultValues;
    private Config $config;

    public function __construct(iterable $providers, Config $config, DefaultValues $defaultValues = null)
    {
        $this->providers = $providers;
        $this->config = $config;
        $this->defaultValues = $defaultValues ?: DefaultValues::none();
    }

    public function build(): RootElementInterface
    {
        if (!$this->config->isIndexed()) {
            return new UrlSet($this->generate(chain(...$this->providers)));
        }

        $index = new SitemapIndex();
        $indexGenerator = function (iterable $providers) use ($index): iterable {
            foreach ($providers as $i => $provider) {
                $index->addGrandChild(new UrlSet($this->generate($provider)));

                yield $this->normalise(new Sitemap($this->getIndexedUrlLoc(++$i)));
            }
        };
        $index->setChildren($indexGenerator(chunk(chain(...$this->providers), $this->config->getLimit())));

        return $index;
    }

    private function normalise(ChildNodeInterface $node): ChildNodeInterface
    {
        if (!$node->getLastModified() && $this->defaultValues->hasLastModified()) {
            $node->setLastModified($this->defaultValues->getLastModified());
        }

        if ($node instanceof Url) {
            if ($node->getPriority() === null && $this->defaultValues->hasPriority()) {
                $node->setPriority($this->defaultValues->getPriority());
            }

            if (!$node->getChangeFrequency() && $this->defaultValues->hasChangeFreq()) {
                $node->setChangeFrequency(ChangeFrequency::create($this->defaultValues->getChangeFrequency()));
            }
        }

        return $node;
    }

    /** @return ChildNodeInterface[] */
    private function generate(iterable $provider): iterable
    {
        foreach ($provider as $entry) {
            if ($entry instanceof Traversable) {
                foreach ($entry as $e) {
                    yield $this->normalise($e);
                }
            } else {
                yield $this->normalise($entry);
            }
        }
    }

    private function getIndexedUrlLoc(int $index): string
    {
        return sprintf('%s/%s', $this->config->getHost(), $this->config->getFileName((string) $index));
    }
}
