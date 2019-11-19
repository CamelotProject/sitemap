<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Provider;

use Camelot\Sitemap\Element;
use Camelot\Sitemap\Routing\UrlGeneratorInterface;

final class Route implements \IteratorAggregate
{
    /** @var \Camelot\Sitemap\Routing\UrlGeneratorInterface */
    private $urlGenerator;

    /** @var array */
    private $routes;

    /** @var DefaultValues */
    private $defaultValues;

    public function __construct(UrlGeneratorInterface $urlGenerator, array $routes, DefaultValues $defaultValues = null)
    {
        $this->urlGenerator = $urlGenerator;
        $this->routes = $routes;
        $this->defaultValues = $defaultValues ?: DefaultValues::none();
    }

    public function getIterator(): iterable
    {
        $defaultRouteData = [
            'changefreq' => null,
            'lastmod' => null,
            'priority' => null,
        ];

        foreach ($this->routes as $route) {
            $route = array_merge($defaultRouteData, $route);

            $url = new Element\Child\Url($this->urlGenerator->generate($route['name'], $route['params']));

            if (($changeFreq = $route['changefreq'] ?: $this->defaultValues->getChangeFreq())) {
                $url->setChangeFreq($changeFreq);
            }

            if (($lastMod = $route['lastmod'] ?: $this->defaultValues->getLastmod())) {
                $url->setLastmod($lastMod);
            }

            if (($priority = $route['priority'] ?: $this->defaultValues->getPriority())) {
                $url->setPriority($priority);
            }

            yield $url;
        }
    }
}
