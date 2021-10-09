<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Command;

use Camelot\Sitemap\Tests\TestCaseFilesystem;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @covers \Camelot\Sitemap\Command\SitemapValidateXmlCommand
 *
 * @internal
 */
final class SitemapValidateXmlCommandTest extends KernelTestCase
{
    public function providerFiles(): iterable
    {
        yield 'Relative sitemap' => ['/XML file.+Expectations.sitemap\\.xml.+is valid/', TestCaseFilesystem::relative(TestCaseFilesystem::expectation('sitemap.xml'))];
        yield 'Relative index' => ['/XML file.+Expectations.indexed.sitemap\\.xml.+is valid/', TestCaseFilesystem::relative(TestCaseFilesystem::expectation('indexed/sitemap.xml'))];

        yield 'Absolute sitemap' => ['/is valid/m', TestCaseFilesystem::expectation('sitemap.xml')];
        yield 'Absolute index' => ['/is valid/m', TestCaseFilesystem::expectation('indexed/sitemap.xml')];

        yield 'Failed validation' => ['/Validation failed /', TestCaseFilesystem::expectation('invalid/sitemap.xml')];
    }

    /**
     * @dataProvider providerFiles
     */
    public function testExecute(string $expected, string $filePath): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('sitemap:validate:xml');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'file' => $filePath,
        ]);

        static::assertMatchesRegularExpression($expected, $commandTester->getDisplay());
    }

    public function testExecuteInvalidPath(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('File "echt.niet" does not exists, or is not readable.');

        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('sitemap:validate:xml');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            'file' => 'echt.niet',
        ]);
    }
}
