<?php

namespace Camelot\Sitemap\Tests\Provider;

use Camelot\Sitemap\Entity\Url;
use Camelot\Sitemap\Provider\Route as RouteProvider;
use Camelot\Sitemap\Tests\Fixtures\News;
use Camelot\Sitemap\UrlGeneratorInterface;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    /**
     * @dataProvider newsDataProvider
     */
    public function testPopulateWithNoResults(array $news, array $newsUrls): void
    {
        $provider = $this->getNewsProvider($news);

        $generatedEntries = iterator_to_array($provider);
        $this->assertEquals($newsUrls, $generatedEntries);
    }

    private function getNewsProvider(array $results)
    {
        $routes = array_map(function($result) {
            return [
                'name' => 'show_news',
                'params' => ['id' => $result->slug]
            ];
        }, $results);

        return new RouteProvider($this->getRouter($results), $routes);
    }

    private function getRouter(array $results)
    {
        $router = $this->createMock(UrlGeneratorInterface::class);

        $valueMap = array_map(function(News $news) {
            return [
                'show_news', ['id' => $news->slug], '/news/' . $news->slug,
            ];
        }, $results);

        $router->method('generate')->willReturnMap($valueMap);

        return $router;
    }

    public function newsDataProvider()
    {
        $first = new News();
        $first->slug = 'first';

        $second = new News();
        $second->slug = 'second';

        $urlFirst = new Url('/news/first');
        $urlSecond = new Url('/news/second');

        return [
            [[], []],
            [[$first, $second], [$urlFirst, $urlSecond]],
        ];
    }
}
