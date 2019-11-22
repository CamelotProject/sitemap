<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Provider;

use Camelot\Sitemap\DefaultValues;
use Camelot\Sitemap\Provider\DataFileProvider;
use Camelot\Sitemap\Sitemap;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;
use function iter\toArray;

/**
 * @covers \Camelot\Sitemap\Provider\DataFileProvider
 *
 * @internal
 */
final class DataFileProviderTest extends TestCase
{
    use ProviderAssertTrait;

    public function providerOptions(): iterable
    {
        $dir = __DIR__ . '/../Fixtures/App/config/sitemap';

        yield 'TXT file only' => [
            [
                [
                    'loc' => 'https://sitemap.camelot.test/path/from-txt-1',
                    'changefreq' => null,
                    'lastmod' => null,
                    'priority' => null,
                ],
            ],
            [
                'file' => "{$dir}/sitemap.txt",
            ],
            null,
        ];

        yield 'YAML file only' => [
            [
                [
                    'loc' => 'https://sitemap.camelot.test/path/from-yaml-1',
                    'changefreq' => null,
                    'lastmod' => null,
                    'priority' => null,
                ],
            ],
            [
                'file' => "{$dir}/sitemap.yaml",
            ],
            null,
        ];

        yield 'File only with default values' => [
            [
                [
                    'loc' => 'https://sitemap.camelot.test/path/from-txt-1',
                    'changefreq' => Sitemap::CHANGE_FREQ_YEARLY,
                    'lastmod' => '2018-07-06T00:00:00+00:00',
                    'priority' => 0.9,
                ],
            ],
            [
                'file' => "{$dir}/sitemap.txt",
            ],
            DefaultValues::create(0.9, Sitemap::CHANGE_FREQ_YEARLY, new DateTimeImmutable('2018-07-06')),
        ];

        yield 'All parameters' => [
            [
                [
                    'loc' => 'https://sitemap.camelot.test/path/from-txt-1',
                    'changefreq' => Sitemap::CHANGE_FREQ_DAILY,
                    'lastmod' => '1999-12-31T00:00:00+00:00',
                    'priority' => 0.3,
                ],
            ],
            [
                'file' => "{$dir}/sitemap.txt",
                'changefreq' => Sitemap::CHANGE_FREQ_DAILY,
                'lastmod' => '1999-12-31T00:00:00+00:00',
                'priority' => 0.3,
            ],
            DefaultValues::create(0.9, Sitemap::CHANGE_FREQ_YEARLY, new DateTimeImmutable('2018-07-06')),
        ];
    }

    /**
     * @dataProvider providerOptions
     */
    public function testProviderGenerator(array $expected, array $options, ?DefaultValues $defaultValues): void
    {
        $iterable = $this->getProvider($options, $defaultValues)->getIterator();
        $result = toArray($iterable);

        static::assertCount(2, $result);
        static::assertProviderArray($expected[0], $result[0]);
    }

    public function testBadOptions(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Provider parameter "file" is required to be set');

        $iterable = $this->getProvider([[]], null)->getIterator();
        toArray($iterable);
    }

    public function testInvalidFileExtension(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Data file test.php extension not supported, only .yaml, .yml, .txt, .conf');

        $iterable = $this->getProvider(['file' => 'test.php'], null)->getIterator();
        toArray($iterable);
    }

    private function getProvider(array $options, ?DefaultValues $defaultValues): DataFileProvider
    {
        return new DataFileProvider($options, $defaultValues);
    }
}
