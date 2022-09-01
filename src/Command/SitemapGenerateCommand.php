<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Command;

use Camelot\Sitemap\Config;
use Camelot\Sitemap\Sitemap;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;

#[AsCommand(
    name: 'sitemap:generate',
    description: 'Generate the sitemap'
)]
class SitemapGenerateCommand extends Command
{
    private iterable $providers;
    private Sitemap $sitemap;
    private Config $config;
    private RouterInterface $router;

    public function __construct(iterable $providers, Sitemap $sitemap, Config $config, RouterInterface $router)
    {
        parent::__construct();

        $this->providers = $providers;
        $this->sitemap = $sitemap;
        $this->config = $config;
        $this->router = $router;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $context = $this->router->getContext();
        $context->setHost($this->config->getHost());

        $io = new SymfonyStyle($input, $output);
        $io->title('Generating Sitemap');

        $this->sitemap->generate($this->providers, $this->config);

        $io->success('Done!');

        return 0;
    }
}
