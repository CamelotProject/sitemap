<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Command;

use Camelot\Sitemap\Exception\ValidationException;
use Camelot\Sitemap\Validation\XmlSchemaValidator;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use function realpath;
use function sprintf;

final class SitemapValidateXmlCommand extends Command
{
    protected static $defaultName = 'sitemap:validate:xml';

    protected function configure(): void
    {
        $this
            ->setDescription('Validate a sitemap file')
            ->addArgument('file', InputArgument::REQUIRED, 'Sitemap XML file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('file');
        $realPath = realpath($filePath);
        if (!$realPath) {
            throw new RuntimeException(sprintf('File "%s" does not exists, or is not readable.', $filePath));
        }

        try {
            XmlSchemaValidator::validateFile($realPath);
        } catch (ValidationException $e) {
            $io->error(['Validation failed', $e->getMessage()]);

            return 1;
        }

        $io->success("XML file $filePath is valid");

        return 0;
    }
}
