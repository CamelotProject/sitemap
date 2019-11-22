<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Fixtures\Doctrine;

use Camelot\Sitemap\Sitemap;
use Camelot\Sitemap\Tests\Fixtures\Element\BigCookbook;
use Camelot\Sitemap\Tests\Fixtures\Element\SitemapMeta;
use Camelot\Sitemap\Tests\Fixtures\Element\TinyChef;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * @internal
 */
final class DoctrineProviderFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $meta = SitemapMeta::create()
            ->setChangeFrequency(Sitemap::CHANGE_FREQ_HOURLY)
            ->setPriority(0.8)
            ->setRouteName('tiny_chef')
            ->setRouteParam('name')
        ;
        $manager->persist($meta);

        $bigCookbookScraps = BigCookbook::create()
            ->setTitle('Of cooking for mice & men')
            ->setPages(187)
            ->setCuisine('scraps')
            ->setUpdated(new DateTimeImmutable('2010-01-02 03:04:05'))
        ;
        $chefMouse = TinyChef::create()
            ->setName('Mouse')
            ->setBigCookbook($bigCookbookScraps)
            ->setUpdated(new DateTimeImmutable('2011-02-03 04:05:06'))
            ->setMeta($meta)
        ;
        $manager->persist($chefMouse);

        $bigCookbookChinese = BigCookbook::create()
            ->setTitle('Bear cat')
            ->setPages(2000)
            ->setCuisine('Chinese')
            ->setUpdated(new DateTimeImmutable('2012-03-04 05:06:07'))
        ;
        $chefPanda = TinyChef::create()
            ->setName('Panda')
            ->setBigCookbook($bigCookbookChinese)
            ->setUpdated(new DateTimeImmutable('2013-04-05 06:07:08'))
            ->setMeta($meta)
        ;
        $manager->persist($chefPanda);

        $manager->flush();
    }
}
