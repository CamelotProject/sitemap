<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Util;

use Camelot\Sitemap\Exception\DomainException;
use function implode;
use function preg_match;
use function sprintf;

final class Assert
{
    /** @codeCoverageIgnore  */
    private function __construct(){}

    public static function stringLength(string $value, int $min, int $max, string $parameterName): void
    {
        $length = \strlen($value);
        if ($length < $min || $length > $max) {
            throw new DomainException(sprintf('Parameter value length was %s, but must be between %s and %s characters. Parameter: "%s" Value: %s', $length, $min, $max, $parameterName, $value));
        }
    }

    public static function oneOf(string $value, array $available, string $parameterName): void
    {
        if (!\in_array($value, $available, true)) {
            throw new DomainException(sprintf('Parameter "%s" value is invalid, %s given. Valid values are: %s', $parameterName, $value, implode(', ', $available)));
        }
    }

    public static function oneOfOrNull(?string $value, array $available, string $parameterName): void
    {
        if ($value !== null) {
            self::oneOf($value, $available, $parameterName);
        }
    }

    /**
     * @param null|float|int $value
     * @param float|int      $min
     * @param float|int      $max
     */
    public static function range($value, $min, $max, string $parameterName): void
    {
        if ($value < $min || $value > $max) {
            throw new DomainException(sprintf('Parameter "%s" value must be between %s and %s, %s given.', $parameterName, $min, $max, $value !== null ? $value : 'null'));
        }
    }

    /**
     * @param null|float|int $value
     * @param float|int      $min
     */
    public static function min($value, $min, string $parameterName): void
    {
        if ($value < $min) {
            throw new DomainException(sprintf('Parameter "%s" value must be at least %s, %s given.', $parameterName, $min, $value !== null ? $value : 'null'));
        }
    }

    /**
     * @param null|float|int $value
     * @param float|int      $max
     */
    public static function max($value, $max, string $parameterName): void
    {
        if ($value > $max) {
            throw new DomainException(sprintf('Parameter "%s" value must be at most %s, %s given.', $parameterName, $max, $value !== null ? $value : 'null'));
        }
    }

    public static function urlHasScheme(string $value): void
    {
        if (!preg_match('/^http(|s):\/\//', $value)) {
            throw new DomainException(sprintf('URLs must be fully-qualified, including the transport method (http/https), %s given.', $value));
        }
    }
}
