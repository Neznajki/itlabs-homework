<?php

namespace App\Repository;

use App\Entity\ChallengeDivision;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChallengeDivision|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChallengeDivision|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChallengeDivision[]    findAll()
 * @method ChallengeDivision[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChallengeDivisionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChallengeDivision::class);
    }

    // /**
    //  * @return ChallengeDivision[] Returns an array of ChallengeDivision objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ChallengeDivision
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
