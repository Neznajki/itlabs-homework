<?php

namespace App\Repository;

use App\Entity\PlayOfSteps;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PlayOfSteps|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayOfSteps|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayOfSteps[]    findAll()
 * @method PlayOfSteps[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayOfStepsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayOfSteps::class);
    }

    // /**
    //  * @return PlayOfSteps[] Returns an array of PlayOfSteps objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlayOfSteps
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
