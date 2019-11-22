<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Fixtures\Repository;

use Camelot\Sitemap\Tests\Fixtures\Element\BigCookbook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method null|BigCookbook find($id, $lockMode = null, $lockVersion = null)
 * @method null|BigCookbook findOneBy(array $criteria, array $orderBy = null)
 * @method BigCookbook[]    findAll()
 * @method BigCookbook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @internal
 */
final class BigCookbookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BigCookbook::class);
    }

    public function getSitemapQuery(): Query
    {
        return $this->createQueryBuilder('tiny_chef')->getQuery();
    }
}
