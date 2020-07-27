<?php

namespace App\Repository;

use App\Entity\DivisionMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DivisionMatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method DivisionMatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method DivisionMatch[]    findAll()
 * @method DivisionMatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DivisionMatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DivisionMatch::class);
    }

    // /**
    //  * @return DivisionMatch[] Returns an array of DivisionMatch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DivisionMatch
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
