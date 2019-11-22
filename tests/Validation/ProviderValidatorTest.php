<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Validation;

use ArrayIterator;
use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Validation\ProviderValidator;
use DomainException;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * @covers \Camelot\Sitemap\Validation\ProviderValidator
 *
 * @internal
 */
final class ProviderValidatorTest extends TestCase
{
    public function providerSingleValid(): iterable
    {
        yield 'Array of URLs' => [
            [
                Url::create('http://sitemap.test/1'),
                Url::create('http://sitemap.test/2'),
            ],
        ];
        yield 'ArrayIterator of URLs' => [
            new ArrayIterator([
                Url::create('http://sitemap.test/1'),
                Url::create('http://sitemap.test/2'),
            ]),
        ];
        yield 'URL Generator' => [
            (function () {
                $urls = [Url::create('http://sitemap.test'), Url::create('http://sitemap.test')];
                foreach ($urls as $url) {
                    yield $url;
                }
            })(),
        ];
    }

    /**
     * @dataProvider providerSingleValid
     */
    public function testValidate(iterable $provider): void
    {
        ProviderValidator::validate($provider);

        $this->addToAssertionCount(1);
    }

    public function providerSingleInvalid(): iterable
    {
        yield 'Provider with empty string' => [['']];
        yield 'Provider with invalid objects' => [[new stdClass()]];
    }

    /**
     * @dataProvider providerSingleInvalid
     */
    public function testValidateException(iterable $provider): void
    {
        $this->expectException(DomainException::class);

        ProviderValidator::validate($provider);
    }

    public function providerMultipleValid(): iterable
    {
        yield 'Array of URLs' => [
            [
                [
                    Url::create('http://sitemap.test/1'),
                    Url::create('http://sitemap.test/2'),
                ],
                new ArrayIterator([
                    Url::create('http://sitemap.test/3'),
                    Url::create('http://sitemap.test/4'),
                ]),
                (function () {
                    $urls = [Url::create('http://sitemap.test/5'), Url::create('http://sitemap.test/6')];
                    foreach ($urls as $url) {
                        yield $url;
                    }
                })(),
            ],
        ];
    }

    /**
     * @dataProvider providerMultipleValid
     */
    public function testValidateMultiple(iterable $providers): void
    {
        ProviderValidator::validateMultiple($providers);

        $this->addToAssertionCount(1);
    }

    public function providerMultipleInvalid(): iterable
    {
        yield [
            [''],
            [new stdClass()],
        ];
    }

    /**
     * @dataProvider providerMultipleInvalid
     */
    public function testValidateMultipleException(iterable $provider): void
    {
        $this->expectException(DomainException::class);

        ProviderValidator::validate($provider);
    }
}
