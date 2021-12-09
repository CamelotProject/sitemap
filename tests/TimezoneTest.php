<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests;

use Camelot\Sitemap\DefaultValues;
use Camelot\Sitemap\Element\Child\Url;
use Camelot\Sitemap\Provider\ProviderTrait;
use Camelot\Sitemap\Sitemap;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;
use function iter\toArray;

final class TimezoneTest extends TestCase
{
    public function providerTimezones(): iterable
    {
        yield ['2018-07-06T00:00:00+00:00', 'UTC'];
        yield ['2018-07-06T00:00:00+02:00', 'Arctic/Longyearbyen'];
        yield ['2018-07-06T00:00:00+10:00', 'Australia/Melbourne'];
        yield ['2018-07-06T00:00:00-04:00', 'America/Santo_Domingo'];
    }

    /** @dataProvider providerTimezones */
    public function testTimezone($expected, string $tz): void
    {
        $options = [
            'route' => 'https://sitemap.camelot.test/url',
        ];
        $defaultValues = DefaultValues::create(0.9, Sitemap::CHANGE_FREQ_YEARLY, new DateTimeImmutable('2018-07-06', new DateTimeZone($tz)));
        $iterable = $this->getProvider([$options], $defaultValues)->getIterator();
        $result = toArray($iterable);

        static::assertCount(1, $result, 'Empty return value from provider');

        /** @var Url $url */
        $url = $result[0];
        static::assertSame($expected, $url->getLastModified());
    }

    private function getProvider(array $options, ?DefaultValues $defaultValues): object
    {
        return new class($options, $defaultValues) { use ProviderTrait; };
    }
}
