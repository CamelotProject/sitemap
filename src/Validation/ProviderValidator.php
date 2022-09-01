<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Validation;

use Camelot\Sitemap\Element\Child\ChildInterface;
use DomainException;
use function is_iterable;
use function sprintf;
use function var_export;
use const PHP_EOL;

/**
 * A validator for provider iterables. Useful for testing & debugging of providers.
 *
 * NOTE: For providers that yield generators this validation will undo any lazy-loading benefits.
 */
final class ProviderValidator
{
    /** @codeCoverageIgnore  */
    private function __construct()
    {
    }

    public static function validate(iterable $provider, bool $debug = false): void
    {
        foreach ($provider as $id => $url) {
            if ($url instanceof ChildInterface) {
                continue;
            }
            $dump = $debug ? PHP_EOL . var_export($url, true) : '';
            $message = sprintf(
                'Provider %s does not implement %s, %s given.%s',
                (string) $id,
                ChildInterface::class,
                \is_object($url) ? \get_class($url) : \gettype($url),
                $dump
            );

            throw new DomainException($message);
        }
    }

    public static function validateMultiple(iterable $providers, bool $debug = false): void
    {
        foreach ($providers as $provider) {
            if (!is_iterable($provider)) {
                throw new DomainException('Provider is not iterable.');
            }

            self::validate($provider, $debug);
        }
    }
}
