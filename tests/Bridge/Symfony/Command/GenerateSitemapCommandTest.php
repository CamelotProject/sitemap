<?php

namespace Camelot\Sitemap\Tests\Bridge\Symfony\Command;

use Camelot\Sitemap\Bridge\Symfony\Command\GenerateSitemapCommand;
use Camelot\Sitemap\Dumper\Memory;
use Camelot\Sitemap\Formatter\Text;
use Camelot\Sitemap\Sitemap;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateSitemapCommandTest extends WebTestCase
{
    public function testSitemapNbUrls(): void
    {
        $kernel = self::createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new GenerateSitemapCommand(new Sitemap(new Memory(), new Text())));

        $command = $application->find('sitemap:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute(['command' => $command->getName()]);

        $this->assertRegExp('/Generating the sitemap/', $commandTester->getDisplay());
        $this->assertRegExp('/Done/', $commandTester->getDisplay());
    }
}
