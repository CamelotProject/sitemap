<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Fixtures;

use Camelot\Sitemap\Sitemap;
use Camelot\Sitemap\Tests\Fixtures\Element\TinyChef;

final class ReferenceConfiguration
{
    private const BASE = [
        'host' => null,
        'file' => 'sitemap.xml',
        'format' => 'xml',
        'limit' => 50000,
        'indexed' => false,
        'compress' => true,
        'providers' => [
            'data_file' => [
                'files' => [],
            ],
            'doctrine' => [
                'queries' => [],
            ],
            'symfony' => [
                'routes' => [],
            ],
        ],
    ];
    private const PROVIDERS = [
        'providers' => [
            'data_file' => [
                'files' => [
                    [
                        'file' => 'blog.xml',
                        'route' => null,
                        'lastmod' => '2010-09-08 07:06:05',
                        'changefreq' => Sitemap::CHANGE_FREQ_NEVER,
                        'priority' => 0.8,
                    ],
                    [
                        'file' => 'events.yaml',
                        'route' => null,
                        'lastmod' => '2008-07-06 05:04:03',
                        'changefreq' => Sitemap::CHANGE_FREQ_ALWAYS,
                        'priority' => 0.9,
                    ],
                ],
            ],
            'doctrine' => [
                'queries' => [
                    [
                        'entity' => TinyChef::class,
                        'method' => 'getSitemapQuery',
                        'properties' => [
                            'changefreq' => null,
                            'lastmod' => 'bigCookbook.updated',
                            'priority' => null,
                            'route_name' => null,
                            'route_params' => ['id' => 'slug'],
                        ],
                        'route' => ['name' => 'events', 'params' => []],
                        'lastmod' => null,
                        'changefreq' => Sitemap::CHANGE_FREQ_NEVER,
                        'priority' => 0.8,
                    ],
                ],
            ],
            'symfony' => [
                'routes' => [
                    [
                        'route' => ['name' => 'blog', 'params' => ['id' => 'publicationDate']],
                        'changefreq' => Sitemap::CHANGE_FREQ_MONTHLY,
                        'lastmod' => '2010-10-10',
                        'priority' => 0.1,
                    ],
                    [
                        'route' => ['name' => 'events', 'params' => ['event' => 'data']],
                    ],
                    [
                        'route' => ['name' => 'people', 'params' => []],
                    ],
                ],
            ],
        ],
    ];

    public static function get(): array
    {
        return static::PROVIDERS + static::BASE;
    }

    public static function getBase(): array
    {
        return static::BASE;
    }

    public static function getOnlyProviders(): array
    {
        return static::PROVIDERS;
    }
}
