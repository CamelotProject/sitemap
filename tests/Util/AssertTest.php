<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Util;

use Camelot\Sitemap\Exception\DomainException;
use Camelot\Sitemap\Util\Assert;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Camelot\Sitemap\Util\Assert
 *
 * @internal
 */
final class AssertTest extends TestCase
{
    public function providerStringLength(): iterable
    {
        yield ['Yes', 3, 6, 'fooBar'];
        yield ['Why no', 3, 6, 'fooBar'];
        yield ['Also', 3, 6, 'fooBar'];
    }

    /**
     * @dataProvider providerStringLength
     */
    public function testStringLength(string $value, int $min, int $max, string $parameterName): void
    {
        Assert::stringLength($value, $min, $max, $parameterName);

        $this->addToAssertionCount(1);
    }

    public function providerStringLengthInvalid(): iterable
    {
        yield ['', 1, 5, 'fooBar'];
        yield ['A string of more than 5', 1, 5, 'fooBar'];
    }

    /**
     * @dataProvider providerStringLengthInvalid
     */
    public function testStringLengthInvalid(string $value, int $min, int $max, string $parameterName): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageMatches("/Parameter value length was [0-9]+, but must be between {$min} and {$max} characters.+/");

        Assert::stringLength($value, $min, $max, $parameterName);
    }

    public function testOneOf(): void
    {
        Assert::oneOf('kittens', ['kittens', 'puppies'], 'fooBar');

        $this->addToAssertionCount(1);
    }

    public function testOneOfInvalid(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageMatches('/Parameter .+ value is invalid, .+ given. Valid values are: .+/');

        Assert::oneOf('fish', ['kittens', 'puppies'], 'fooBar');
    }

    public function providerOneOfOrNull(): iterable
    {
        yield [null, ['kittens', 'puppies'], 'fooBar'];
        yield ['kittens', ['kittens', 'puppies'], 'fooBar'];
    }

    /**
     * @dataProvider providerOneOfOrNull
     */
    public function testOneOfOrNull(?string $value, array $available, string $parameterName): void
    {
        Assert::oneOfOrNull($value, $available, $parameterName);

        $this->addToAssertionCount(1);
    }

    public function providerOneOfOrNullInvalid(): iterable
    {
        yield ['', ['kittens', 'puppies'], 'fooBar'];
        yield ['fish', ['kittens', 'puppies'], 'fooBar'];
    }

    /**
     * @dataProvider providerOneOfOrNullInvalid
     */
    public function testOneOfOrNullInvalid(?string $value, array $available, string $parameterName): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageMatches("/Parameter .{$parameterName}. value is invalid, {$value} given. Valid values are: .+/");

        Assert::oneOfOrNull($value, $available, $parameterName);
    }

    public function providerRange(): iterable
    {
        yield [1, 1, 5, 'fooBar'];
        yield [5, 1, 5, 'fooBar'];
        yield [2, 1, 2.5, 'fooBar'];
        yield [2, 1.9, 5, 'fooBar'];
        yield [1.5, 0.3, 2.5, 'fooBar'];
    }

    /**
     * @dataProvider providerRange
     *
     * @param null|float|int $value
     * @param float|int      $min
     * @param float|int      $max
     */
    public function testRange($value, $min, $max, string $parameterName): void
    {
        Assert::range($value, $min, $max, $parameterName);

        $this->addToAssertionCount(1);
    }

    public function providerRangeInvalid(): iterable
    {
        yield [null, 1, 5, 'fooBar'];
        yield [0, 1, 5, 'fooBar'];
        yield [9, 1, 1.5, 'fooBar'];
        yield [7, 1.9, 5, 'fooBar'];
        yield [0.1, 0.3, 2.5, 'fooBar'];
    }

    /**
     * @dataProvider providerRangeInvalid
     *
     * @param null|float|int $value
     * @param float|int      $min
     * @param float|int      $max
     */
    public function testRangeInvalid($value, $min, $max, string $parameterName): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageMatches("/Parameter .{$parameterName}. value must be between {$min} and {$max}, .+ given/");

        Assert::range($value, $min, $max, $parameterName);
    }

    public function testMin(): void
    {
        Assert::min(2, 1, 'test');

        $this->addToAssertionCount(1);
    }

    public function testMinInvalid(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageMatches('/value must be at least 2, 1 given/');

        Assert::min(1, 2, 'test');
    }

    public function testMax(): void
    {
        Assert::max(9, 10, 'test');

        $this->addToAssertionCount(1);
    }

    public function testMaxInvalid(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageMatches('/value must be at most 1, 2 given/');

        Assert::max(2, 1, 'test');
    }

    public function providerUrlScheme(): iterable
    {
        yield ['http://www.sitemap.test'];
        yield ['https://secure.sitemap.test'];
    }

    /**
     * @dataProvider providerUrlScheme
     */
    public function testUrlHasScheme(string $value): void
    {
        Assert::urlHasScheme($value);

        $this->addToAssertionCount(1);
    }

    public function providerUrlSchemeInvalid(): iterable
    {
        yield ['/relative/url'];
        yield ['//no-schema.sitemap.test'];
        yield ['ftp://ftp.sitemap.test'];
        yield ['ssh://private.sitemap.test'];
    }

    /**
     * @dataProvider providerUrlSchemeInvalid
     */
    public function testUrlHasSchemeInvalid(string $value): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageMatches('/URLs must be fully-qualified, including the transport method \\(http\\/https\\), .+ given/');

        Assert::urlHasScheme($value);
    }
}
