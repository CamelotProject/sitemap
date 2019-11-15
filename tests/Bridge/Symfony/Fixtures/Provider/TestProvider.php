<?php

namespace Camelot\Sitemap\Tests\Bridge\Symfony\Fixtures\Provider;

use Camelot\Sitemap\Entity\Url;
use Camelot\Sitemap\Entity\Video;
use DateTimeImmutable;
use IteratorAggregate;

class TestProvider implements IteratorAggregate
{
    public function getIterator()
    {
        $url = new Url('http://www.google.fr');
        $url->setChangeFreq('never');
        $url->setLastmod(new DateTimeImmutable('2012-12-19 02:28'));

        $video = new Video('Grilling steaks for summer', 'Alkis shows you how to get perfectly done steaks every time', 'http://www.example.com/thumbs/123.jpg');
        $url->addVideo($video);

        yield $url;

        $url = new Url('http://github.com');
        $url->setChangeFreq('always');
        $url->setPriority(0.2);

        yield $url;
    }
}
