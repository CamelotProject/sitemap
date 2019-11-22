<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Builder;

use ArrayIterator;
use Camelot\Sitemap\Builder\Builder;
use Camelot\Sitemap\Config;
use Camelot\Sitemap\DefaultValues;
use Camelot\Sitemap\Element;
use Camelot\Sitemap\Sitemap;
use Camelot\Sitemap\Validation\ProviderValidator;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use function iter\toArray;

/**
 * @covers \Camelot\Sitemap\Builder\Builder
 *
 * @internal
 */
final class BuilderTest extends TestCase
{
    public function providerBuild(): iterable
    {
        $host = 'https://sitemap.test';
        $providers = $this->getProviders();
        $config = new Config($host, __DIR__);

        yield 'Empty defaults' => [
            $providers, $config, DefaultValues::none(),
        ];

        yield 'With defaults' => [
            $providers, $config, DefaultValues::create(0.3, Sitemap::CHANGE_FREQ_ALWAYS, new DateTimeImmutable()),
        ];
    }

    /**
     * @dataProvider providerBuild
     */
    public function testBuild(iterable $providers, Config $config, DefaultValues $defaultValues): void
    {
        $urlSet = (new Builder($providers, $config, $defaultValues))->build();
        static::assertInstanceOf(Element\UrlSet::class, $urlSet);

        $urls = toArray($urlSet->getChildren());
        static::assertCount(5, $urls);

        /** @var Element\Child\Url $url */
        foreach ($urls as $url) {
            static::assertInstanceOf(Element\Child\Url::class, $url);
            static::assertSame($defaultValues->getChangeFrequency(), $url->getChangeFrequency());
            static::assertSame($defaultValues->getPriority(), $url->getPriority());
            static::assertSame($defaultValues->getLastModified() ? $defaultValues->getLastModified()->format(DateTimeInterface::W3C) : $defaultValues->getLastModified(), $url->getLastModified());
        }
    }

    public function providerBuildIndexed(): iterable
    {
        $host = 'https://sitemap.test';
        $providers = $this->getProviders();
        $config = new Config($host, __DIR__, null, null, false, true, 2);

        yield 'Empty defaults' => [
            $providers, $config, DefaultValues::none(),
        ];

        yield 'With defaults' => [
            $providers, $config, DefaultValues::create(0.3, Sitemap::CHANGE_FREQ_ALWAYS, new DateTimeImmutable()),
        ];
    }

    /**
     * @dataProvider providerBuildIndexed
     */
    public function testBuildIndexed(iterable $providers, Config $config, DefaultValues $defaultValues): void
    {
        $sitemapIndex = (new Builder($providers, $config, $defaultValues))->build();
        static::assertInstanceOf(Element\SitemapIndex::class, $sitemapIndex);

        $sitemaps = toArray($sitemapIndex->getChildren());

        static::assertCount(3, $sitemaps);
        foreach ($sitemaps as $index => $sitemap) {
            ++$index;

            static::assertInstanceOf(Element\Child\Sitemap::class, $sitemap);
            static::assertSame("https://sitemap.test/sitemap-{$index}.xml", $sitemap->getLoc());
            static::assertSame($defaultValues->getLastModified() ? $defaultValues->getLastModified()->format(DateTimeInterface::W3C) : $defaultValues->getLastModified(), $sitemap->getLastModified());
        }

        /** @var Element\UrlSet[] $urlSets */
        $urlSets = toArray($sitemapIndex->getGrandChildren());
        static::assertCount(3, $urlSets);

        /** @var Element\UrlSet $urlSet */
        foreach ($urlSets as $urlSet) {
            static::assertInstanceOf(Element\UrlSet::class, $urlSet);

            $urls = toArray($urlSet->getChildren());
            foreach ($urls as $url) {
                static::assertSame($defaultValues->getChangeFrequency(), $url->getChangeFrequency());
                static::assertSame($defaultValues->getPriority(), $url->getPriority());
                static::assertSame($defaultValues->getLastModified() ? $defaultValues->getLastModified()->format(DateTimeInterface::W3C) : $defaultValues->getLastModified(), $url->getLastModified());
            }
        }
    }

    private function getProviders(): iterable
    {
        $provider1 = [
            new Element\Child\Url('https://sitemap.test/url1'),
            new Element\Child\Url('https://sitemap.test/url2'),
        ];
        $provider2 = [
            new Element\Child\Url('https://sitemap.test/url3'),
        ];
        $provider3 = [
            new Element\Child\Url('https://sitemap.test/url4'),
            new Element\Child\Url('https://sitemap.test/url5'),
        ];
        $providers = new ArrayIterator([$provider1, $provider2, $provider3]);

        ProviderValidator::validateMultiple($providers);

        return $providers;
    }
}
