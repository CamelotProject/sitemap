<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Provider;

use Camelot\Sitemap\DefaultValues;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use RuntimeException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Traversable;
use function sprintf;

/**
 * Populate a sitemap using a Doctrine entity.
 *
 * The options available are the following:
 *  * entity:     The entity to use
 *  * method:     Repository method that returns a Doctrine\Orm\Query object
 *  * route:      Array describing how to generate an URL with the router
 *  * lastmod:    Name of the lastmod attribute (can be null)
 *  * priority:   The priority to apply to all the elements (can be null)
 *  * changefreq: The changefreq to apply to all the elements (can be null)
 *
 * Example:
 *  [
 *      'entity' => 'AcmeDemoBundle:News',
 *      'method' => 'getSitemapQuery',
 *      'lastmod' => 'updatedAt',
 *      'priority' => 0.6,
 *      'route' => [
 *          'name' => 'show_news',
 *          'params' => ['id' => 'slug']
 *      ],
 *  ]
 */
trait DoctrineTrait
{
    use SymfonyRouteTrait {
        SymfonyRouteTrait::__construct as doConstructRouteProvider;
    }

    private EntityManagerInterface $em;
    private PropertyAccessor $accessor;

    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, array $options, DefaultValues $defaultValues = null)
    {
        $this->doConstructRouteProvider($urlGenerator, $options, $defaultValues);
        $this->em = $em;
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    /** Populate a sitemap using a Doctrine entity. */
    public function getIterator(): Traversable
    {
        foreach ($this->getQuery()->toIterable() as $result) {
            yield $this->getUrlEntity($this->normalizeOptions($this->transformResult($result)));
        }

        $this->em->clear();
    }

    protected function transformResult(object $result): array
    {
        $entry = $this->options;

        if ($entry['properties']['lastmod'] ?? null) {
            $lastModified = $this->getColumnValue($result, $entry['properties']['lastmod']);
            $entry['lastmod'] = $lastModified instanceof DateTimeInterface ? $lastModified->format(DateTimeInterface::W3C) : $lastModified;
        }
        if ($entry['properties']['changefreq'] ?? null) {
            $entry['changefreq'] = $this->getColumnValue($result, $entry['properties']['changefreq']);
        }
        if ($entry['properties']['priority'] ?? null) {
            $entry['priority'] = $this->getColumnValue($result, $entry['properties']['priority']);
        }
        if ($entry['properties']['route_name'] ?? null) {
            $entry['route']['name'] = $this->getColumnValue($result, $entry['properties']['route_name']);
        }
        if ($entry['properties']['route_params'] ?? null) {
            $params = [];
            foreach ($entry['properties']['route_params'] ?? [] as $id => $value) {
                $params[$id] = $this->getColumnValue($result, $value);
            }
            $entry['route']['params'] = $params;
        }

        return $entry;
    }

    protected function getQuery(): Query
    {
        $method = $this->options['method'] ?? null;
        $repo = $this->em->getRepository($this->options['entity']);
        $query = $method ? $repo->{$method}() : $repo->createQueryBuilder('o')->getQuery();

        if (!$query instanceof Query) {
            throw new RuntimeException(sprintf('Expected instance of Query, got %s (see method %s:%s)', \get_class($query), $this->options['entity'], $method)); // @codeCoverageIgnore
        }

        return $query;
    }

    protected function getColumnValue(object $result, string $column)
    {
        return $this->accessor->getValue($result, $column);
    }
}
