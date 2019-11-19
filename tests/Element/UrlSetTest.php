<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Element;

use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\UrlSet;
use PHPUnit\Framework\TestCase;
use function iter\toArray;

/**
 * @covers \Camelot\Sitemap\Element\UrlSet
 *
 * @internal
 */
final class UrlSetTest extends TestCase
{
    public function providerChildren(): iterable
    {
        yield [[]];
        yield [[new Url('https://sitemap.test/index.html')]];
        yield [[new Url('https://sitemap.test/index.html'), new Url('https://sitemap.test/blog/index.html')]];
    }

    /**
     * @dataProvider providerChildren
     */
    public function testChildren(iterable $urls): void
    {
        $urlSet = new UrlSet($urls);

        static::assertSame(toArray($urls), toArray($urlSet->getChildren()));
    }

    /**
     * @dataProvider providerChildren
     */
    public function testSetChildren(iterable $urls): void
    {
        $urlSet = new UrlSet();

        static::assertSame(toArray($urls), toArray($urlSet->setChildren($urls)->getChildren()));
    }

    /**
     * @dataProvider providerChildren
     */
    public function testAddChild(iterable $urls): void
    {
        $urlSet = new UrlSet();
        foreach ($urls as $url) {
            $urlSet->addChild($url);
        }

        static::assertSame(toArray($urls), toArray($urlSet->getChildren()));
    }
}
