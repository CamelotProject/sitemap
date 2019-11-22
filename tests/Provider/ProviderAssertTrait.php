<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Provider;

use Camelot\Sitemap\Element\Child\Url;

/**
 * @method static void assertEquals($expected, $actual, string $message = '', float $delta = 0.0, int $maxDepth = 10, bool $canonicalize = false, bool $ignoreCase = false)
 */
trait ProviderAssertTrait
{
    private static function assertProviderArray(array $expected, Url $url): void
    {
        $result = [
            'loc' => $url->getLoc(),
            'changefreq' => $url->getChangeFrequency(),
            'lastmod' => $url->getLastModified(),
            'priority' => $url->getPriority(),
        ];

        static::assertSame($expected, $result);
    }
}
