<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Fixtures\Provider;

use Camelot\Sitemap\Element;
use Camelot\Sitemap\Element\Child\AlternateUrl;
use Camelot\Sitemap\Element\Child\ChangeFrequency;
use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\Child\Video;
use Camelot\Sitemap\Element\Child\VideoContentSegmentLocation;
use Camelot\Sitemap\Element\Child\VideoGalleryLocation;
use Camelot\Sitemap\Element\Child\VideoId;
use Camelot\Sitemap\Element\Child\VideoPlatform;
use Camelot\Sitemap\Element\Child\VideoPlayerLocation;
use Camelot\Sitemap\Element\Child\VideoPrice;
use Camelot\Sitemap\Element\Child\VideoRestriction;
use Camelot\Sitemap\Element\Child\VideoTvShow;
use Camelot\Sitemap\Element\Child\VideoUploader;
use Camelot\Sitemap\Sitemap;
use DateTimeImmutable;
use IteratorAggregate;
use Traversable;

/**
 * @internal
 */
final class FunctionalTestProvider implements IteratorAggregate
{
    public function getIterator(): Traversable
    {
        yield $this->providerOne();
        yield $this->providerTwo();
        yield $this->providerThree();
    }

    private function providerOne(): iterable
    {
        $url = new Url('http://sitemap.test/articles');
        $url->setChangeFrequency(new ChangeFrequency(Sitemap::CHANGE_FREQ_NEVER));
        $url->setLastModified(new DateTimeImmutable('2012-12-19 02:28'));

        $url->addAlternateUrl(new AlternateUrl('nl-nl', 'http://nl.sitemap.test/articles'));

        yield $url;

        $url = new Url('http://github.com');
        $url->setChangeFrequency(new ChangeFrequency(Sitemap::CHANGE_FREQ_ALWAYS));
        $url->setPriority(0.2);

        yield $url;
    }

    private function providerTwo(): iterable
    {
        $video = (new Video('My video', 'A video we made', 'https://video.test/thumbnail.png'))
            ->setContentLoc('https://video.test/vid.mp4')
            ->setPlayerLoc(new VideoPlayerLocation('http://sitemap.test/player.js'))
            ->setDuration(69)
            ->setExpirationDate(new DateTimeImmutable('2020-01-01T00:00:00+00:00'))
            ->setRating(4.2)
            ->addContentSegmentLocation(new VideoContentSegmentLocation('https://sitemap.test/video-1.ogg', 60))
            ->addContentSegmentLocation(new VideoContentSegmentLocation('https://sitemap.test/video-2.ogg', 45))
            ->setViewCount(9001)
            ->setPublicationDate(new DateTimeImmutable('2001-01-01'))
            ->setTags(['kittens', 'pandas', 'alpacas'])
            ->setCategory('Fun stuff')
            ->setFamilyFriendly(false)
            ->setRestriction(new VideoRestriction(VideoRestriction::ALLOW, ['NL', 'AU']))
            ->setGalleryLoc(new VideoGalleryLocation('http://sitemap.test/video-gallery', 'Our video gallery'))
            ->addPrice(new VideoPrice('USD', 24.96, VideoPrice::TYPE_PURCHASE, VideoPrice::RESOLUTION_STANDARD))
            ->addPrice(new VideoPrice('EUR', 42.69, VideoPrice::TYPE_RENT, VideoPrice::RESOLUTION_HIGH))
            ->setRequiresSubscription(true)
            ->setUploader(new VideoUploader('John Doe', 'https://john.test'))
            ->setTvShow(new VideoTvShow('My TV Show', 'full', 'Season 2, Episode 1', 2, 1, new DateTimeImmutable('2020-01-01')))
            ->setPlatform(new VideoPlatform(VideoPlatform::ALLOW, [VideoPlatform::TV, VideoPlatform::WEB]))
            ->setLive(false)
            ->addId(new VideoId('0d2c6e71-9ac3-4ef4-a6c2-899562e19ddc', VideoId::TYPE_TMS_PROGRAM))
            ->addId(new VideoId('45062486-c8f7-4596-9530-e78db82c3a36', VideoId::TYPE_TMS_SERIES))
        ;
        yield (new Url('https://sitemap.test/uri/one'))
            ->setPriority(0.5)
            ->setChangeFrequency(ChangeFrequency::daily())
            ->setLastModified(new DateTimeImmutable('2012-06-01'))
            ->addVideo($video)
        ;

        $image = (new Element\Child\Image('https://sitemap.test/image.png'))
            ->setTitle('Images everywhere')
            ->setCaption('Something, something, caption sauce')
            ->setLicense('MIT')
            ->setGeoLocation('Borkeld, Overijssel')
        ;
        yield (new Url('https://sitemap.test/uri/two'))
            ->setLastModified(new DateTimeImmutable('1999-12-31'))
            ->addAlternateUrl(new AlternateUrl('nl', 'https://nl.sitemap.test'))
            ->addImage($image)
        ;
    }

    private function providerThree(): iterable
    {
        yield new Url('https://sitemap.test/uri/three');
        yield new Url('https://sitemap.test/uri/four');
        yield new Url('https://sitemap.test/uri/five');
    }
}
