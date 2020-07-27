<?php

namespace App\Repository;

use App\Entity\ChallengePlayOfStep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ChallengePlayOfStep|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChallengePlayOfStep|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChallengePlayOfStep[]    findAll()
 * @method ChallengePlayOfStep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChallengePlayOfStepRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChallengePlayOfStep::class);
    }

    // /**
    //  * @return ChallengePlayOfStep[] Returns an array of ChallengePlayOfStep objects
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
    public function findOneBySomeField($value): ?ChallengePlayOfStep
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
