<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Provider;

use IteratorAggregate;

/**
 * @see DoctrineTrait
 */
final class DoctrineProvider implements IteratorAggregate
{
    use DoctrineTrait;
}
