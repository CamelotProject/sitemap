<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Controller;

use ArrayIterator;
use Camelot\Sitemap\Config;
use Camelot\Sitemap\Sitemap;
use Camelot\Sitemap\Tests\Fixtures\Provider\ControllerTestProvider;
use Camelot\Sitemap\Tests\TestCaseFilesystem;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use function date_default_timezone_set;
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
        date_default_timezone_set('UTC');

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
        yield [0, 'http://www.google.fr', 'never', null, new DateTimeImmutable('2012-12-19T02:28:00+00:00')]; // changefreq is "never", so the time is skipped
        yield [1, 'http://github.com', 'always', 0.2, null];
    }

    public function testSitemap(): void
    {
        $client = static::createClient();
        $client->request('GET', '/sitemap.xml');

        static::assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    /**
     * @dataProvider urlsProvider
     * @depends      testSitemap
     */
    public function testSitemapUrl(int $pos, string $loc, string $changeFrequency, ?float $priority, ?DateTimeInterface $lastModified): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sitemap.xml');

        static::assertSame($loc, $crawler->filterXPath('//default:urlset/default:url')->eq($pos)->filterXPath('//default:loc')->text());
    }

    /**
     * @dataProvider urlsProvider
     * @depends      testSitemap
     */
    public function testSitemapUrlChangeFreq(int $pos, string $loc, string $changeFrequency, ?float $priority, ?DateTimeInterface $lastModified): void
    {
        static::assertSame($changeFrequency, $this->getUrlNodeCrawler($pos)->filterXPath('//default:changefreq')->text());
    }

    /**
     * @dataProvider urlsProvider
     * @depends      testSitemap
     */
    public function testSitemapUrlPriority(int $pos, string $loc, string $changeFrequency, ?float $priority, ?DateTimeInterface $lastModified): void
    {
        $priorityNode = $this->getUrlNodeCrawler($pos)->filterXPath('//default:priority');
        if ($priority === null) {
            static::assertCount(0, $priorityNode);
        } else {
            static::assertSame((string) $priority, $priorityNode->text());
        }
    }

    /**
     * @dataProvider urlsProvider
     * @depends      testSitemap
     */
    public function testSitemapUrlLastMod(int $pos, string $loc, string $changeFrequency, ?float $priority, ?DateTimeInterface $lastModified): void
    {
        $lastModifiedNode = $this->getUrlNodeCrawler($pos)->filterXPath('//default:lastmod');
        if ($lastModified === null) {
            static::assertCount(0, $lastModifiedNode);
        } else {
            static::assertSame($lastModified->format('c'), $lastModifiedNode->text());
        }
    }

    private function getUrlNodeCrawler(int $pos): Crawler
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/sitemap.xml');

        return $crawler->filterXPath('//default:url')->eq($pos);
    }
}
