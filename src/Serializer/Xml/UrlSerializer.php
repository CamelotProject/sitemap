<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;

final class UrlSerializer implements XmlSerializerInterface
{
    /** @param Url $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            [
                'name' => Sitemap::XML_CLARK_NS . 'loc',
                'value' => $object->getLoc(),
                'attributes' => [],
            ],
        ];

        if ($object->getLastModified()) {
            $meta[] = [
                'name' => Sitemap::XML_CLARK_NS . 'lastmod',
                'value' => $object->getLastModified(),
                'attributes' => [],
            ];
        }
        if ($object->getChangeFrequency()) {
            $meta[] = [
                'name' => Sitemap::XML_CLARK_NS . 'changefreq',
                'value' => $object->getChangeFrequency(),
                'attributes' => [],
            ];
        }
        if ($object->getPriority()) {
            $meta[] = [
                'name' => Sitemap::XML_CLARK_NS . 'priority',
                'value' => $object->getPriority(),
                'attributes' => [],
            ];
        }

        foreach ($object->getAlternateUrls() as $alternateUrl) {
            $meta[] = [
                'name' => Sitemap::XHTML_CLARK_NS . 'link',
                'attributes' => [
                    'rel' => 'alternate',
                    'href' => $alternateUrl->getUrl(),
                    'hreflang' => $alternateUrl->getLocale(),
                ],
            ];
        }

        foreach ($object->getImages() as $image) {
            $meta[] = [
                'name' => Sitemap::IMAGE_XML_CLARK_NS . 'image',
                'value' => $image,
                'attributes' => [],
            ];
        }

        foreach ($object->getVideos() as $video) {
            $meta[] = [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'video',
                'value' => $video,
                'attributes' => [],
            ];
        }

        $writer->write($meta);
    }
}
