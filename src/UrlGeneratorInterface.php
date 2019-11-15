<?php

declare(strict_types=1);

namespace Camelot\Sitemap;

interface UrlGeneratorInterface
{
    /**
     * Generates a URL or path for a specific route based on the given parameters.
     *
     * Parameters that reference placeholders in the route pattern will substitute them in the
     * path or host. Extra params are added as query string to the URL.
     *
     * @param string $name The name of the route
     * @param mixed $parameters A list of parameters
     *
     * @return string The generated URL
     */
    public function generate(string $name, $parameters = []): string;
}
