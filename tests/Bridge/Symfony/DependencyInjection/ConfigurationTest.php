<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Bridge\Symfony\DependencyInjection;

use Camelot\Sitemap\Bridge\Symfony\DependencyInjection\Configuration;
use Camelot\Sitemap\Tests\Fixtures\ReferenceConfiguration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

/**
 * @covers \Camelot\Sitemap\Bridge\Symfony\DependencyInjection\Configuration
 *
 * @internal
 */
final class ConfigurationTest extends TestCase
{
    public function providerConfiguration(): iterable
    {
        yield 'Empty options' => [
            ReferenceConfiguration::getBase(),
            [],
        ];

        yield 'Only providers' => [
            ReferenceConfiguration::get(),
            ReferenceConfiguration::getOnlyProviders(),
        ];

        yield 'Only single config file provider 1' => [
            [
                'providers' => [
                    'data_file' => [
                        'files' => [
                            [
                                'file' => 'events.yaml',
                                'route' => null,
                            ],
                        ],
                    ],
                    'doctrine' => [
                        'queries' => [],
                    ],
                    'symfony' => [
                        'routes' => [],
                    ],
                ],
            ] + ReferenceConfiguration::getBase(),
            [
                'providers' => [
                    'data_file' => 'events.yaml',
                ],
            ],
        ];

        yield 'Only single config file provider 2' => [
            [
                'providers' => [
                    'data_file' => [
                        'files' => [
                            [
                                'file' => 'events.yaml',
                                'route' => null,
                            ],
                        ],
                    ],
                    'doctrine' => [
                        'queries' => [],
                    ],
                    'symfony' => [
                        'routes' => [],
                    ],
                ],
            ] + ReferenceConfiguration::getBase(),
            [
                'providers' => [
                    'data_file' => ['files' => 'events.yaml'],
                ],
            ],
        ];
    }

    /**
     * @dataProvider providerConfiguration
     */
    public function testGetConfigTreeBuilder(array $expected, array $config): void
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $processed = $processor->processConfiguration($configuration, [$config]);

        static::assertSame($expected, $processed);
    }
}
