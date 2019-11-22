<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Provider;

use Camelot\Sitemap\DefaultValues;
use Camelot\Sitemap\Provider\SymfonyRouteProvider;
use Camelot\Sitemap\Sitemap;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use function iter\toArray;

/**
 * @covers \Camelot\Sitemap\Provider\SymfonyRouteProvider
 * @covers \Camelot\Sitemap\Provider\SymfonyRouteProviderTrait
 *
 * @internal
 */
final class SymfonyRouteProviderTest extends TestCase
{
    use ProviderAssertTrait;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    protected function setUp(): void
    {
        $routes = new RouteCollection();
        $route = new Route('/tests/{id}');
        $routes->add('tests', $route);
        $context = new RequestContext('', 'GET', 'sitemap.test', 'https');

        $this->urlGenerator = new UrlGenerator($routes, $context);
    }

    public function providerOptions(): iterable
    {
        yield 'Route only' => [
            [
                [
                    'loc' => 'https://sitemap.test/tests/snail',
                    'changefreq' => null,
                    'lastmod' => null,
                    'priority' => null,
                ],
            ],
            [
                [
                    'route' => ['name' => 'tests', 'params' => ['id' => 'snail']],
                ],
            ],
            null,
        ];

        yield 'Route only with default values' => [
            [
                [
                    'loc' => 'https://sitemap.test/tests/snail',
                    'changefreq' => Sitemap::CHANGE_FREQ_YEARLY,
                    'lastmod' => '2018-07-06T00:00:00+00:00',
                    'priority' => 0.9,
                ],
            ],
            [
                [
                    'route' => ['name' => 'tests', 'params' => ['id' => 'snail']],
                ],
            ],
            DefaultValues::create(0.9, Sitemap::CHANGE_FREQ_YEARLY, new DateTimeImmutable('2018-07-06')),
        ];

        yield 'All parameters' => [
            [
                [
                    'loc' => 'https://sitemap.test/tests/snail',
                    'changefreq' => Sitemap::CHANGE_FREQ_DAILY,
                    'lastmod' => '1999-12-31T00:00:00+00:00',
                    'priority' => 0.3,
                ],
            ],
            [
                [
                    'changefreq' => Sitemap::CHANGE_FREQ_DAILY,
                    'lastmod' => '1999-12-31T00:00:00+00:00',
                    'route' => ['name' => 'tests', 'params' => ['id' => 'snail']],
                    'priority' => 0.3,
                ],
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

        static::assertProviderArray($expected[0], $result[0]);
    }

    public function testBadOptions(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Provider parameter "route.name" is required to be set');

        $iterable = $this->getProvider([[]], null)->getIterator();
        toArray($iterable);
    }

    private function getProvider(array $options, ?DefaultValues $defaultValues): SymfonyRouteProvider
    {
        return new SymfonyRouteProvider($this->urlGenerator, $options, $defaultValues);
    }
}
