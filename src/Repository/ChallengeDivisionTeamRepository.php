<?php

namespace App\Repository;

use App\Entity\ChallengeDivisionTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChallengeDivisionTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChallengeDivisionTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChallengeDivisionTeam[]    findAll()
 * @method ChallengeDivisionTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChallengeDivisionTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChallengeDivisionTeam::class);
    }

    // /**
    //  * @return ChallengeDivisionTeam[] Returns an array of ChallengeDivisionTeam objects
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
    public function findOneBySomeField($value): ?ChallengeDivisionTeam
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
