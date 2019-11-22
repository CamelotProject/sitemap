<?php

namespace Camelot\Sitemap\Tests\Command;

use Camelot\Sitemap\Command\SitemapGenerateCommand;
use Camelot\Sitemap\Dumper\Memory;
use Camelot\Sitemap\Generator\TextGenerator;
use Camelot\Sitemap\Sitemap;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class SitemapGenerateCommandTest extends WebTestCase
{
    public function testSitemapNbUrls(): void
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new SitemapGenerateCommand(new Sitemap(new Memory(), new TextGenerator())));

        $command = $application->find('sitemap:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertRegExp('/Generating the sitemap/', $commandTester->getDisplay());
        $this->assertRegExp('/Done/', $commandTester->getDisplay());
    }
}
