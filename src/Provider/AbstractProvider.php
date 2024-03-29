<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Provider;

use Camelot\Sitemap\Entity\Url;
use Camelot\Sitemap\Routing\UrlGeneratorInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Abstract class containing common methods used by Propel and Doctrine providers.
 */
abstract class AbstractProvider
{
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $accessor;

    /**
     * @var \Camelot\Sitemap\Routing\UrlGeneratorInterface
     */
    private $urlGenerator;

    protected $options = [
        'loc' => [],
        'lastmod' => null,
    ];

    public function __construct(UrlGeneratorInterface $urlGenerator, array $options)
    {
        $this->urlGenerator = $urlGenerator;
        $this->options = array_merge($this->options, $options);

        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

    protected function resultToUrl($result): Url
    {
        $url = new Url($this->getResultLoc($result));

        if ($this->options['lastmod'] !== null) {
            $url->setLastmod($this->getColumnValue($result, $this->options['lastmod']));
        }

        return $url;
    }

    protected function getResultLoc($result): string
    {
        $route = $this->options['loc']['route'];
        $params = [];

        if (!isset($this->options['loc']['params'])) {
            $this->options['loc']['params'] = [];
        }

        foreach ($this->options['loc']['params'] as $key => $column) {
            $params[$key] = $this->getColumnValue($result, $column);
        }

        return $this->urlGenerator->generate($route, $params);
    }

    protected function getColumnValue($result, string $column)
    {
        return $this->accessor->getValue($result, $column);
    }
}
