<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Provider;

use Camelot\Sitemap\DefaultValues;
use Camelot\Sitemap\Element\Child\Url;
use DomainException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function sprintf;
use function var_export;
use const PHP_EOL;

/**
 * Populate a sitemap using a Symfony route.
 *
 * The options available are the following:
 *  * route:      Array describing how to generate an URL with the router
 *  * lastmod:    Name of the lastmod attribute (can be null)
 *  * priority:   The priority to apply to all the elements (can be null)
 *  * changefreq: The changefreq to apply to all the elements (can be null)
 *
 * Example:
 *  [
 *      'lastmod' => '2019-01-02',
 *      'priority' => 0.6,
 *      'route' => [
 *          'name' => 'show_news',
 *          'params' => ['id' => 'slug']
 *      ],
 *  ]
 */
trait SymfonyRouteTrait
{
    use ProviderTrait {
        ProviderTrait::__construct as doConstructProvider;
    }

    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator, array $options, DefaultValues $defaultValues = null)
    {
        $this->urlGenerator = $urlGenerator;
        $this->doConstructProvider($options, $defaultValues);
    }

    protected function getUrlEntity(array $entry): Url
    {
        return $this->setUrlParameters(new Url($this->getRouteUrl($entry)), $entry);
    }

    protected function getRouteUrl(array $entry): string
    {
        self::assertRoute($entry);

        $params = [];
        foreach ($entry['route']['params'] ?? [] as $key => $value) {
            $params[$key] = $value;
        }

        return $this->urlGenerator->generate($entry['route']['name'], $params, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    protected static function assertRoute(array $entry): void
    {
        if (!isset($entry['route']['name'])) {
            throw new DomainException(sprintf('Provider parameter "route.name" is required to be set. Passed:%s%s', PHP_EOL, var_export($entry, true)));
        }
    }
}
