<?php

namespace Camelot\Sitemap\Tests\Bridge\Symfony\Fixtures\Provider;

use Camelot\Sitemap\Element\Child\ChangeFrequency;
use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\Child\Video;
use Camelot\Sitemap\Sitemap;
use DateTimeImmutable;
use IteratorAggregate;

class TestProvider implements IteratorAggregate
{
    public function getIterator()
    {
        $url = new Url('http://www.google.fr');
        $url->setChangeFrequency(new ChangeFrequency(Sitemap::CHANGE_FREQ_NEVER));
        $url->setLastModified(new DateTimeImmutable('2012-12-19 02:28'));

        $video = new Video('Grilling steaks for summer', 'Alkis shows you how to get perfectly done steaks every time', 'http://www.example.com/thumbs/123.jpg');
        $url->addVideo($video);

        yield $url;

        $url = new Url('http://github.com');
        $url->setChangeFrequency(new ChangeFrequency(Sitemap::CHANGE_FREQ_ALWAYS));
        $url->setPriority(0.2);

        yield $url;
    }
}
