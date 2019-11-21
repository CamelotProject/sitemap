<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Generator;

use Camelot\Sitemap\Element\RootElementInterface;
use Camelot\Sitemap\Exception\GeneratorException;
use Camelot\Sitemap\Target\TargetInterface;

interface GeneratorInterface
{
    /**
     * Generate a sitemap document and output to a TargetInterface object.
     *
     * @param RootElementInterface $data   the data to generate a sitemap document
     * @param TargetInterface      $target output target for the generated sitemap document content
     *
     * @throws GeneratorException throw when output encounters an error
     */
    public function generate(RootElementInterface $data, TargetInterface $target): void;
}
