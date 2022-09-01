<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Generator;

use Camelot\Sitemap\Element\Child;
use Camelot\Sitemap\Element\RootElementInterface;
use Camelot\Sitemap\Element\SitemapIndex;
use Camelot\Sitemap\Element\UrlSet;
use Camelot\Sitemap\Exception\GeneratorException;
use Camelot\Sitemap\Serializer\Xml\ImageSerializer;
use Camelot\Sitemap\Serializer\Xml\SitemapIndexSerializer;
use Camelot\Sitemap\Serializer\Xml\SitemapSerializer;
use Camelot\Sitemap\Serializer\Xml\UrlSerializer;
use Camelot\Sitemap\Serializer\Xml\UrlSetSerializer;
use Camelot\Sitemap\Serializer\Xml\VideoContentSegmentLocationSerializer;
use Camelot\Sitemap\Serializer\Xml\VideoGalleryLocationSerializer;
use Camelot\Sitemap\Serializer\Xml\VideoIdSerializer;
use Camelot\Sitemap\Serializer\Xml\VideoPlatformSerializer;
use Camelot\Sitemap\Serializer\Xml\VideoPlayerLocationSerializer;
use Camelot\Sitemap\Serializer\Xml\VideoPriceSerializer;
use Camelot\Sitemap\Serializer\Xml\VideoRestrictionSerializer;
use Camelot\Sitemap\Serializer\Xml\VideoSerializer;
use Camelot\Sitemap\Serializer\Xml\VideoTvShowSerializer;
use Camelot\Sitemap\Serializer\Xml\VideoUploaderSerializer;
use Camelot\Sitemap\Sitemap;
use Camelot\Sitemap\Target\TargetInterface;
use Sabre\Xml\Writer;
use function sprintf;

final class XmlGenerator implements GeneratorInterface
{
    private array $namespaceMap = [];
    private array $classMap = [];
    private array $valueObjectMap = [];

    public function generate(RootElementInterface $data, TargetInterface $target): void
    {
        if ($data instanceof UrlSet) {
            $rootElementName = Sitemap::XML_CLARK_NS . 'urlset';
            $xmlNs = $this->configureUrlSet();
        } elseif ($data instanceof SitemapIndex) {
            $rootElementName = Sitemap::XML_CLARK_NS . 'sitemapindex';
            $xmlNs = $this->configureSitemapIndex();
        } else {
            throw new GeneratorException(sprintf('Unknown %s object, %s & %s supported, %s given.', RootElementInterface::class, UrlSet::class, SitemapIndex::class, \get_class($data)));
        }
        $output = $this->doGenerate($rootElementName, $data, $xmlNs);
        $target->write($output);
    }

    /**
     * Writes a value object that must be previously registered using mapValueObject().
     *
     * @throws GeneratorException
     */
    private function doGenerate(string $rootElementName, RootElementInterface $element, string $contextUri = null): string
    {
        $w = new Writer();
        $w->namespaceMap = $this->namespaceMap;
        $w->classMap = $this->classMap;

        $w->openMemory();
        $w->contextUri = $contextUri;
        $w->setIndent(true);
        $w->startDocument();
        $w->writeElement($rootElementName, $element);

        return $w->outputMemory();
    }

    private function configureSitemapIndex(): string
    {
        $this->configure()
            ->addObjectMap(Sitemap::XML_CLARK_NS . 'site', Child\Sitemap::class)
            ->addObjectMap(Sitemap::XML_CLARK_NS . 'sitemapindex', SitemapIndex::class)

            ->addClassMap(SitemapIndex::class, [SitemapIndexSerializer::class, 'serialize'])
            ->addClassMap(Child\Sitemap::class, [SitemapSerializer::class, 'serialize'])
        ;

        return Sitemap::XML_CLARK_NS . 'sitemapindex';
    }

    private function configureUrlSet(): string
    {
        $this->configure()
            ->addNamespaceMap(Sitemap::XHTML_NS, 'xhtml')
            ->addNamespaceMap(Sitemap::IMAGE_XML_NS, 'image')
            ->addNamespaceMap(Sitemap::VIDEO_XML_NS, 'video')
        ;

        return Sitemap::XML_CLARK_NS . 'urlset';
    }

    private function configure(): self
    {
        $this->namespaceMap = [];
        $this->classMap = [];
        $this->valueObjectMap = [];
        $this
            ->addNamespaceMap(Sitemap::XML_NS, '')

            ->addObjectMap(Sitemap::XML_CLARK_NS . 'urlset', UrlSet::class)
            ->addObjectMap(Sitemap::XML_CLARK_NS . 'url', Child\Url::class)

            ->addClassMap(UrlSet::class, [UrlSetSerializer::class, 'serialize'])
            ->addClassMap(Child\Url::class, [UrlSerializer::class, 'serialize'])
            ->addClassMap(Child\Image::class, [ImageSerializer::class, 'serialize'])
            ->addClassMap(Child\Video::class, [VideoSerializer::class, 'serialize'])
            ->addClassMap(Child\VideoContentSegmentLocation::class, [VideoContentSegmentLocationSerializer::class, 'serialize'])
            ->addClassMap(Child\VideoGalleryLocation::class, [VideoGalleryLocationSerializer::class, 'serialize'])
            ->addClassMap(Child\VideoId::class, [VideoIdSerializer::class, 'serialize'])
            ->addClassMap(Child\VideoPlatform::class, [VideoPlatformSerializer::class, 'serialize'])
            ->addClassMap(Child\VideoPlayerLocation::class, [VideoPlayerLocationSerializer::class, 'serialize'])
            ->addClassMap(Child\VideoPrice::class, [VideoPriceSerializer::class, 'serialize'])
            ->addClassMap(Child\VideoRestriction::class, [VideoRestrictionSerializer::class, 'serialize'])
            ->addClassMap(Child\VideoTvShow::class, [VideoTvShowSerializer::class, 'serialize'])
            ->addClassMap(Child\VideoUploader::class, [VideoUploaderSerializer::class, 'serialize'])
        ;

        return $this;
    }

    private function addNamespaceMap(string $namespace, string $alias): self
    {
        $this->namespaceMap[$namespace] = $alias;

        return $this;
    }

    private function addClassMap(string $className, callable $serializer): self
    {
        $this->classMap[$className] = $serializer;

        return $this;
    }

    private function addObjectMap(string $elementName, string $className): self
    {
        $this->valueObjectMap[$className] = $elementName;

        return $this;
    }
}
