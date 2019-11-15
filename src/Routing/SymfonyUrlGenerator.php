<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Routing;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface as SymfonyUrlGeneratorInterface;

final class SymfonyUrlGenerator implements UrlGeneratorInterface
{
    private $originalGenerator;

    public function __construct(SymfonyUrlGeneratorInterface $generator)
    {
        $this->originalGenerator = $generator;
    }

    public function generate(string $name, $parameters = []): string
    {
        return $this->originalGenerator->generate($name, $parameters);
    }
}
