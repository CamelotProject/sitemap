<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Provider;

use IteratorAggregate;

final class SymfonyRouteProvider implements IteratorAggregate
{
    use SymfonyRouteTrait;
}
