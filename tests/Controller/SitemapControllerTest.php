<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Controller;

use ArrayIterator;
use Camelot\Sitemap\Config;
use Camelot\Sitemap\Sitemap;
use Camelot\Sitemap\Tests\Fixtures\Provider\ControllerTestProvider;
use Camelot\Sitemap\Tests\TestCaseFilesystem;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use function dirname;

/**
 * @covers \Camelot\Sitemap\Controller\SitemapController
 * @group functional
 *
 * @internal
 */
final class SitemapControllerTest extends WebTestCase
{
    private static string $targetFile;

    public static function setUpBeforeClass(): void
    {
        static::$targetFile = TestCaseFilesystem::public('sitemap.xml');

        $fs = new Filesystem();
        if ($fs->exists(static::$targetFile)) {
            $fs->remove(static::$targetFile);
        }
        $provider = new ArrayIterator([new ControllerTestProvider()]);
        $sitemap = new Sitemap();
        $sitemap->generate($provider, new Config('https://sitemap.test', dirname(static::$targetFile)));
    }

    public static function tearDownAfterClass(): void
    {
        $fs = new Filesystem();
        $fs->remove(static::$targetFile);
    }

    public function testSitemapUrlCount(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sitemap.xml');

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
        static::assertCount(2, $crawler->filterXPath('//default:url'));
    }

    public function urlsProvider(): iterable
    {
        yield [0, 'http://www.google.fr', 'never', null, '2012-12-19T02:28:00+00:00']; // changefreq is "never", so the time is skipped
        yield [1, 'http://github.com', 'always', 0.2, null];
    }

    /**
     * @dataProvider urlsProvider
     */
    public function testSitemapUrl(int $pos, string $loc, string $changeFrequency, ?float $priority, ?string $lastModified): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sitemap.xml');

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        static::assertSame($loc, $crawler->filterXPath('//default:urlset/default:url')->eq($pos)->filterXPath('//default:loc')->text());
        static::assertSame($changeFrequency, $crawler->filterXPath('//default:url')->eq($pos)->filterXPath('//default:changefreq')->text());

        if ($priority === null) {
            static::assertCount(0, $crawler->filterXPath('//default:url')->eq($pos)->filterXPath('//default:priority'));
        } else {
            static::assertSame((string) $priority, $crawler->filterXPath('//default:url')->eq($pos)->filterXPath('//default:priority')->text());
        }

        if ($lastModified === null) {
            static::assertCount(0, $crawler->filterXPath('//default:url')->eq($pos)->filterXPath('//default:lastmod'));
        } else {
            static::assertSame($lastModified, $crawler->filterXPath('//default:url')->eq($pos)->filterXPath('//default:lastmod')->text());
        }
    }
}
