<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Provider;

use Camelot\Sitemap\DefaultValues;
use Camelot\Sitemap\Provider\DoctrineProvider;
use Camelot\Sitemap\Sitemap;
use Camelot\Sitemap\Tests\Fixtures\Element\TinyChef;
use Camelot\Sitemap\Tests\FunctionalTestTrait;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use function date_default_timezone_set;
use function iter\toArray;

/**
 * Test assumption:.
 *
 * - A table of TinyChef entities with two rows:
 *   - id,name,updated,big_cookbook_id,meta_id
 *   - 1,'Mouse','2011-02-03 04:05:06',1,1
 *   - 2,'Panda','2013-04-05 06:07:08',2,1
 * - A table of BigCookbook entities with two rows:
 *   - id,title,pages,cuisine,updated
 *   - 1,'Of cooking for mice & men',187,'scraps','2010-01-02 03:04:05'
 *   - 2,'Bear cat',2000,'Chinese','2012-03-04 05:06:07'
 * - A table of SitemapMeta relating to the TinyChef entities, with one row
 *   - id,change_frequency,priority,route_name,route_param
 *   - 1,'hourly',0.8,'tiny-chef','name'
 *
 * @covers \Camelot\Sitemap\Provider\DoctrineProvider
 * @covers \Camelot\Sitemap\Provider\DoctrineTrait
 * @group functional
 *
 * @see \Camelot\Sitemap\Tests\Fixtures\Doctrine\DoctrineProviderFixtures
 *
 * @internal
 */
final class DoctrineProviderTest extends KernelTestCase
{
    use FunctionalTestTrait;

    /** @var EntityManager */
    private $em;
    /** @var MockObject|UrlGeneratorInterface */
    private $urlGenerator;

    protected function setUp(): void
    {
        date_default_timezone_set('UTC');

        static::bootKernel();
        $this->setUpDb();
        $this->em = self::$container->get('doctrine')->getManager();

        $routes = new RouteCollection();
        $routes->add('tiny_chef', new Route('/tiny-chefs/{id}'));
        $context = new RequestContext('', 'GET', 'sitemap.test', 'https');

        $this->urlGenerator = new UrlGenerator($routes, $context);
    }

    public function providerOptions(): iterable
    {
        yield 'All options' => [
            [
                [
                    'route' => 'https://sitemap.test/tiny-chefs/Mouse',
                    'lastModified' => '2010-01-02T03:04:05+00:00',
                    'changeFrequency' => Sitemap::CHANGE_FREQ_HOURLY,
                    'priority' => 0.8,
                ],
                [
                    'route' => 'https://sitemap.test/tiny-chefs/Panda',
                    'lastModified' => '2012-03-04T05:06:07+00:00',
                    'changeFrequency' => Sitemap::CHANGE_FREQ_HOURLY,
                    'priority' => 0.8,
                ],
            ],
            [
                'entity' => TinyChef::class,
                'method' => 'getSitemapQuery',
                'properties' => [
                    'changefreq' => 'meta.changeFrequency',
                    'lastmod' => 'bigCookbook.updated',
                    'priority' => 'meta.priority',
                    'route_name' => 'meta.routeName',
                    'route_params' => ['id' => 'name'],
                ],
            ],
            null,
        ];

        yield 'All options minus query' => [
            [
                [
                    'route' => 'https://sitemap.test/tiny-chefs/Mouse',
                    'lastModified' => '2010-01-02T03:04:05+00:00',
                    'changeFrequency' => Sitemap::CHANGE_FREQ_HOURLY,
                    'priority' => 0.8,
                ],
                [
                    'route' => 'https://sitemap.test/tiny-chefs/Panda',
                    'lastModified' => '2012-03-04T05:06:07+00:00',
                    'changeFrequency' => Sitemap::CHANGE_FREQ_HOURLY,
                    'priority' => 0.8,
                ],
            ],
            [
                'entity' => TinyChef::class,
                'properties' => [
                    'changefreq' => 'meta.changeFrequency',
                    'lastmod' => 'bigCookbook.updated',
                    'priority' => 'meta.priority',
                    'route_name' => 'meta.routeName',
                    'route_params' => ['id' => 'name'],
                ],
            ],
            null,
        ];

        yield 'Minimal options with DefaultValues passed to provider' => [
            [
                [
                    'route' => 'https://sitemap.test/tiny-chefs/Mouse',
                    'lastModified' => '2019-12-31T00:00:00+00:00',
                    'changeFrequency' => Sitemap::CHANGE_FREQ_WEEKLY,
                    'priority' => 0.2,
                ],
                [
                    'route' => 'https://sitemap.test/tiny-chefs/Panda',
                    'lastModified' => '2019-12-31T00:00:00+00:00',
                    'changeFrequency' => Sitemap::CHANGE_FREQ_WEEKLY,
                    'priority' => 0.2,
                ],
            ],
            [
                'entity' => TinyChef::class,
                'route' => ['name' => 'tiny_chef'],
                'properties' => [
                    'route_params' => ['id' => 'name'],
                ],
            ],
            DefaultValues::create(0.2, Sitemap::CHANGE_FREQ_WEEKLY, new DateTimeImmutable('2019-12-31', new DateTimeZone('UTC'))),
        ];
    }

    /**
     * @dataProvider providerOptions
     */
    public function testGetIterator(array $expected, array $options, ?DefaultValues $defaultValues): array
    {
        $provider = $this->getProvider($options, $defaultValues);
        $tinyChefs = toArray($provider->getIterator());

        static::assertCount(2, $tinyChefs);
        static::assertProviderArray($expected, $tinyChefs);

        return $tinyChefs;
    }

    private static function assertProviderArray(array $expected, array $tinyChefs): void
    {
        $mouse = $tinyChefs[0];
        $panda = $tinyChefs[1];

        $actual = [
            [
                'route' => $mouse->getLoc(),
                'lastModified' => $mouse->getLastModified(),
                'changeFrequency' => $mouse->getChangeFrequency(),
                'priority' => $mouse->getPriority(),
            ],
            [
                'route' => $panda->getLoc(),
                'lastModified' => $panda->getLastModified(),
                'changeFrequency' => $panda->getChangeFrequency(),
                'priority' => $panda->getPriority(),
            ],
        ];

        static::assertSame($expected, $actual);
    }

    private function getProvider(array $options, ?DefaultValues $defaultValues): DoctrineProvider
    {
        return new DoctrineProvider($this->em, $this->urlGenerator, $options, $defaultValues);
    }
}
