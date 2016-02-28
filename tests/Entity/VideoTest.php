<?php

namespace SitemapGenerator\Tests\Entity;

use SitemapGenerator\Entity\Video;

class VideoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \DomainException
     */
    public function testTitleMaxLength()
    {
        $video = new Video();
        $video->setTitle(str_repeat('o', 100));
        $this->assertTrue(true);

        $video->setTitle(str_repeat('o', 101));
    }

    /**
     * @expectedException \DomainException
     */
    public function testDescriptionMaxLength()
    {
        $video = new Video();
        $video->setDescription(str_repeat('o', 2048));
        $this->assertTrue(true);

        $video->setDescription(str_repeat('o', 2049));
    }

    /**
     * @dataProvider invalidDurationProvider
     * @expectedException \DomainException
     */
    public function testInvalidDuration($duration)
    {
        $video = new Video();
        $video->setDuration($duration);
    }

    /**
     * @dataProvider dateProvider
     */
    public function testExpirationDate($date, $expected_date)
    {
        $video = new Video();
        $video->setExpirationDate($date);
        $this->assertSame($video->getExpirationDate(), $expected_date);
    }

    /**
     * @dataProvider dateProvider
     */
    public function testPublicationDate($date, $expected_date)
    {
        $video = new Video();
        $video->setPublicationDate($date);
        $this->assertSame($video->getPublicationDate(), $expected_date);
    }

    /**
     * @dataProvider invalidRatingProvider
     * @expectedException \DomainException
     */
    public function testInvalidRating($rating)
    {
        $video = new Video();
        $video->setRating($rating);
    }

    /**
     * @expectedException \DomainException
     */
    public function testInvalidViewCount()
    {
        $video = new Video();
        $video->setViewCount(-1);
    }

    /**
     * @expectedException \DomainException
     */
    public function testInvalidTagsCount()
    {
        $video = new Video();
        $video->setTags(array_pad([], 33, 'tag'));
    }

    /**
     * @expectedException \DomainException
     */
    public function testCategoryMaxLength()
    {
        $video = new Video();
        $video->setCategory(str_repeat('o', 256));
        $this->assertTrue(true);

        $video->setCategory(str_repeat('o', 257));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidRestriction()
    {
        $video = new Video();
        $video->setRestrictions(['fr', 'en'], 'foo');
    }

    /**
     * @expectedException \DomainException
     */
    public function testInvalidPlatform()
    {
        $video = new Video();
        $video->setPlatforms([Video::PLATFORM_TV => Video::RESTRICTION_DENY, 'foo' => Video::RESTRICTION_DENY]);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidPlatformRelationship()
    {
        $video = new Video();
        $video->setPlatforms([Video::PLATFORM_TV => Video::RESTRICTION_DENY, Video::PLATFORM_MOBILE => 'foo']);
    }

    public function invalidDurationProvider()
    {
        return [
            [-1],
            [28801],
        ];
    }

    public function invalidRatingProvider()
    {
        return [
            [-1],
            [6],
        ];
    }

    public function dateProvider()
    {
        return [
            [null, null],
            [new \DateTime('2012-12-20'), $this->dateFormatW3C('2012-12-20')],
        ];
    }

    protected function dateFormatW3C($date)
    {
        $date = new \DateTime($date);

        return $date->format(\DateTime::W3C);
    }
}
