<?php

namespace Camelot\Sitemap\Bridge\Symfony\Controller;

use Camelot\Sitemap\Sitemap;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends AbstractController
{
    public function __invoke(Sitemap $sitemap)
    {
        return new Response($sitemap->build(), Response::HTTP_OK, [
            'Content-Type' => 'application/xml',
        ]);
    }
}
