<?php

declare(strict_types=1);

namespace Camelot\Sitemap\Tests\Fixtures\Repository;

use Camelot\Sitemap\Tests\Fixtures\Element\TinyChef;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method null|TinyChef find($id, $lockMode = null, $lockVersion = null)
 * @method null|TinyChef findOneBy(array $criteria, array $orderBy = null)
 * @method TinyChef[]    findAll()
 * @method TinyChef[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @internal
 */
final class TinyChefRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TinyChef::class);
    }

    public function getSitemapQuery(): Query
    {
        return $this->createQueryBuilder('tiny_chef')->getQuery();
    }
}
