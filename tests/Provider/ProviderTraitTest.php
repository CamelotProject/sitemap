<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Provider;

use Camelot\Sitemap\DefaultValues;
use Camelot\Sitemap\Provider\ProviderTrait;
use Camelot\Sitemap\Sitemap;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use function date_default_timezone_set;
use function iter\toArray;

/**
 * @covers \Camelot\Sitemap\Provider\ProviderTrait
 *
 * @internal
 */
final class ProviderTraitTest extends TestCase
{
    use ProviderAssertTrait;

    protected function setUp(): void
    {
        date_default_timezone_set('UTC');
    }

    public function providerOptions(): iterable
    {
        yield 'Empty input' => [[], [], null];

        yield 'Route only' => [
            [
                [
                    'loc' => 'https://sitemap.camelot.test/url',
                    'changefreq' => null,
                    'lastmod' => null,
                    'priority' => null,
                ],
            ],
            [
                [
                    'route' => 'https://sitemap.camelot.test/url',
                ],
            ],
            null,
        ];

        yield 'Route only with default values' => [
            [
                [
                    'loc' => 'https://sitemap.camelot.test/url',
                    'changefreq' => Sitemap::CHANGE_FREQ_YEARLY,
                    'lastmod' => '2018-07-06T00:00:00+00:00',
                    'priority' => 0.9,
                ],
            ],
            [
                [
                    'route' => 'https://sitemap.camelot.test/url',
                ],
            ],
            DefaultValues::create(0.9, Sitemap::CHANGE_FREQ_YEARLY, new DateTimeImmutable('2018-07-06', new DateTimeZone('UTC'))),
        ];

        yield 'All parameters' => [
            [
                [
                    'loc' => 'https://sitemap.camelot.test/url',
                    'changefreq' => Sitemap::CHANGE_FREQ_DAILY,
                    'lastmod' => '1999-12-31T00:00:00+00:00',
                    'priority' => 0.3,
                ],
            ],
            [
                [
                    'changefreq' => Sitemap::CHANGE_FREQ_DAILY,
                    'lastmod' => '1999-12-31T00:00:00+00:00',
                    'route' => 'https://sitemap.camelot.test/url',
                    'priority' => 0.3,
                ],
            ],
            DefaultValues::create(0.9, Sitemap::CHANGE_FREQ_YEARLY, new DateTimeImmutable('2018-07-06', new DateTimeZone('UTC'))),
        ];
    }

    /**
     * @dataProvider providerOptions
     */
    public function testProviderGenerator(array $expected, array $options, ?DefaultValues $defaultValues): void
    {
        $iterable = $this->getProviderTrait($options, $defaultValues)->getIterator();
        $result = toArray($iterable);

        if ($expected) {
            static::assertProviderArray($expected[0], $result[0]);
        } else {
            static::assertEmpty($result);
        }
    }

    private function getProviderTrait(array $options, ?DefaultValues $defaultValues): object
    {
        return new class($options, $defaultValues) { use ProviderTrait; };
    }
}
