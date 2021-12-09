<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Fixtures\Repository;

use Camelot\Sitemap\Tests\Fixtures\Element\SitemapMeta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|SitemapMeta find($id, $lockMode = null, $lockVersion = null)
 * @method null|SitemapMeta findOneBy(array $criteria, array $orderBy = null)
 * @method SitemapMeta[]    findAll()
 * @method SitemapMeta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @internal
 */
final class SitemapMetaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SitemapMeta::class);
    }
}
