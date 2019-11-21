<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Serializer\Xml;

use Camelot\Sitemap\Element\Child\Video;
use Camelot\Sitemap\Sitemap;
use Sabre\Xml\Writer;
use function implode;

final class VideoSerializer implements XmlSerializerInterface
{
    /** @param Video $object */
    public static function serialize(Writer $writer, object $object): void
    {
        $meta = [
            [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'thumbnail_loc',
                'value' => $object->getThumbnailLoc(),
                'attributes' => [],
            ],
            [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'title',
                'value' => $object->getTitle(),
                'attributes' => [],
            ],
            [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'description',
                'value' => $object->getDescription(),
                'attributes' => [],
            ],
        ];

        if ($object->getContentLoc()) {
            $meta[] = [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'content_loc',
                'value' => $object->getContentLoc(),
                'attributes' => [],
            ];
        }
        if ($object->getPlayerLoc()) {
            $meta[] = $object->getPlayerLoc();
        }
        if ($object->getDuration()) {
            $meta[] = [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'duration',
                'value' => $object->getDuration(),
                'attributes' => [],
            ];
        }
        if ($object->getExpirationDate()) {
            $meta[] = [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'expiration_date',
                'value' => $object->getExpirationDate(),
                'attributes' => [],
            ];
        }
        if ($object->getRating()) {
            $meta[] = [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'rating',
                'value' => $object->getRating(),
                'attributes' => [],
            ];
        }
        if ($object->getContentSegmentLocations()) {
            $meta[] = $object->getContentSegmentLocations();
        }
        if ($object->getViewCount()) {
            $meta[] = [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'view_count',
                'value' => $object->getViewCount(),
                'attributes' => [],
            ];
        }
        if ($object->getPublicationDate()) {
            $meta[] = [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'publication_date',
                'value' => $object->getPublicationDate(),
                'attributes' => [],
            ];
        }
        if ($object->getTags()) {
            $meta[] = [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'tag',
                'value' => implode(' ', $object->getTags()),
                'attributes' => [],
            ];
        }
        if ($object->getCategory()) {
            $meta[] = [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'category',
                'value' => $object->getCategory(),
                'attributes' => [],
            ];
        }
        if ($object->isFamilyFriendly() !== null) {
            $meta[] = [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'family_friendly',
                'value' => $object->isFamilyFriendly() ? 'yes' : 'no',
                'attributes' => [],
            ];
        }
        if ($object->getRestriction()) {
            $meta[] = $object->getRestriction();
        }
        if ($object->getGalleryLoc()) {
            $meta[] = $object->getGalleryLoc();
        }
        if ($object->getPrices()) {
            $meta[] = $object->getPrices();
        }
        if ($object->isRequiresSubscription() !== null) {
            $meta[] = [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'requires_subscription',
                'value' => $object->isRequiresSubscription() ? 'yes' : 'no',
                'attributes' => [],
            ];
        }
        if ($object->getUploader()) {
            $meta[] = $object->getUploader();
        }
        if ($object->getTvShow()) {
            $meta[] = $object->getTvShow();
        }
        if ($object->getPlatform()) {
            $meta[] = $object->getPlatform();
        }
        if ($object->isLive() !== null) {
            $meta[] = [
                'name' => Sitemap::VIDEO_XML_CLARK_NS . 'live',
                'value' => $object->isLive() ? 'yes' : 'no',
                'attributes' => [],
            ];
        }
        if ($object->getIds()) {
            $meta[] = $object->getIds();
        }

        $writer->write($meta);
    }
}
