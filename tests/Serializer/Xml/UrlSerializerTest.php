<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Serializer\Xml;

use Camelot\Sitemap\Element\Child\AlternateUrl;
use Camelot\Sitemap\Element\Child\ChangeFrequency;
use Camelot\Sitemap\Element\Child\Image;
use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Element\Child\Video;
use Camelot\Sitemap\Serializer\Xml\UrlSerializer;
use Camelot\Sitemap\Sitemap;
use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Serializer\Xml\UrlSerializer
 *
 * @internal
 */
final class UrlSerializerTest extends TestCase
{
    use XmlWriterMockTrait;

    private const BASE_META = [
        'name' => Sitemap::XML_CLARK_NS . 'loc',
        'value' => 'https://sitemap.test/index.html',
        'attributes' => [],
    ];

    public function testSerialize(): void
    {
        $writer = $this->createWriterMock([self::BASE_META])
        ;

        UrlSerializer::serialize($writer, new Url('https://sitemap.test/index.html'));
    }

    public function testSerializeWithLastModified(): void
    {
        $lastModified = new DateTimeImmutable('2000-01-01');

        $writer = $this->createWriterMock([
            self::BASE_META,
            [
                'name' => Sitemap::XML_CLARK_NS . 'lastmod',
                'value' => $lastModified->format(DateTimeInterface::W3C),
                'attributes' => [],
            ],
        ])
        ;

        UrlSerializer::serialize($writer, (new Url('https://sitemap.test/index.html'))->setLastModified($lastModified));
    }

    public function testSerializeWithChangeFrequency(): void
    {
        $changeFrequency = ChangeFrequency::hourly();

        $writer = $this->createWriterMock([
            self::BASE_META,
            [
                'name' => Sitemap::XML_CLARK_NS . 'changefreq',
                'value' => $changeFrequency->get(),
                'attributes' => [],
            ],
        ])
        ;

        UrlSerializer::serialize($writer, (new Url('https://sitemap.test/index.html'))->setChangeFrequency($changeFrequency));
    }

    public function testSerializeWithPriority(): void
    {
        $writer = $this->createWriterMock([
            self::BASE_META,
            [
                'name' => Sitemap::XML_CLARK_NS . 'priority',
                'value' => 0.7,
                'attributes' => [],
            ],
        ])
        ;

        UrlSerializer::serialize($writer, (new Url('https://sitemap.test/index.html'))->setPriority(0.7));
    }

    public function testSerializeWithAlternateUrl(): void
    {
        $altUrl = new AlternateUrl('nl', 'https://nl.sitemap.test');

        $writer = $this->createWriterMock([
            self::BASE_META,
            [
                'name' => Sitemap::XHTML_CLARK_NS . 'link',
                'attributes' => [
                    'rel' => 'alternate',
                    'href' => 'https://nl.sitemap.test',
                    'hreflang' => 'nl',
                ],
            ],
        ])
        ;

        UrlSerializer::serialize($writer, (new Url('https://sitemap.test/index.html'))->addAlternateUrl($altUrl));
    }

    public function testSerializeWithImage(): void
    {
        $image = new Image('https://sitemap.test/mary.png');

        $writer = $this->createWriterMock([
            self::BASE_META,
            [
                'name' => Sitemap::IMAGE_XML_CLARK_NS . 'image',
                'value' => $image,
                'attributes' => [],
            ],
        ])
        ;

        UrlSerializer::serialize($writer, (new Url('https://sitemap.test/index.html'))->addImage($image));
    }

    public function testSerializeWithVideo(): void
    {
        $video = new Video('SaM', 'There is something about', 'https://sitemap.test/thumbnail.png');

        $writer = $this->createWriterMock([
            self::BASE_META,
            [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'video',
                'value' => $video,
                'attributes' => [],
            ],
        ])
        ;

        UrlSerializer::serialize($writer, (new Url('https://sitemap.test/index.html'))->addVideo($video));
    }
}
