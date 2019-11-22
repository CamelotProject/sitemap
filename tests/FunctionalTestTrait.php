<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;

trait FunctionalTestTrait
{
    private function setUpDb(): void
    {
        if (!(new Filesystem())->exists(TestCaseFilesystem::fixtureDb())) {
            $application = new Application(self::$kernel ?: self::bootKernel());
            $application->find('doctrine:schema:update')->run(new ArrayInput(['--force' => true]), new BufferedOutput());
            $application->find('doctrine:fixtures:load')->run(new ArrayInput(['--append' => true]), new BufferedOutput());
        }
    }
}
