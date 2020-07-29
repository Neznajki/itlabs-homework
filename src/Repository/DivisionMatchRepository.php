<?php

namespace App\Repository;

use App\Entity\ChallengeDivision;
use App\Entity\DivisionMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
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

    /**
     * @param ChallengeDivision $challengeDivision
     * @return DivisionMatch[]
     */
    public function getMatchesByChallengeDivision(ChallengeDivision $challengeDivision): array
    {
        return $this->createQueryBuilder('d')
            ->join('d.teamA', 'ta')
            ->andWhere('ta.challengeDivision = :challengeId')
            ->setParameter('challengeId', $challengeDivision->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * @param DivisionMatch $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(DivisionMatch $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }

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
