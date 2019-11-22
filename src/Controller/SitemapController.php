<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Controller;

use Camelot\Sitemap\Config;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

final class SitemapController
{
    public function __invoke(Config $config): Response
    {
        return new BinaryFileResponse($config->getFilePath(), Response::HTTP_OK, ['Content-Type' => $config->getContentType()]);
    }
}
