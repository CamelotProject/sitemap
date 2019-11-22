<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Command;

use Camelot\Sitemap\Tests\FunctionalTestTrait;
use Camelot\Sitemap\Tests\TestCaseFilesystem;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \Camelot\Sitemap\Command\SitemapGenerateCommand
 * @group functional
 *
 * @internal
 */
final class SitemapGenerateCommandTest extends KernelTestCase
{
    use FunctionalTestTrait;

    public static function setUpBeforeClass(): void
    {
        (new Filesystem())->remove(TestCaseFilesystem::public('sitemap.xml'));
    }

    protected function setUp(): void
    {
        $this->setUpDb();
    }

    public function testExecute(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('sitemap:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        static::assertRegExp('/Generating Sitemap/', $commandTester->getDisplay());
        static::assertRegExp('/Done/', $commandTester->getDisplay());
    }
}
