<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Target;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class Console implements TargetInterface
{
    /** @var OutputInterface */
    private $handle;

    public function __construct(InputInterface $input = null, OutputInterface $output = null)
    {
        $this->handle = new SymfonyStyle($input ?: new ArrayInput([]), $output ?: new ConsoleOutput());
    }

    public function write(string $string): void
    {
        $this->handle->write($string);
    }
}
