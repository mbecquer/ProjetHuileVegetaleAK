<?php

namespace App\Repository;

use App\Entity\Oil;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Oil|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oil|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oil[]    findAll()
 * @method Oil[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OilRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oil::class);
    }

    // /**
    //  * @return Oil[] Returns an array of Oil objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Oil
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
