<?php

namespace App\Repository;

use App\Entity\Challenge;
use App\Entity\PlayOfMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use RuntimeException;

/**
 * @method PlayOfMatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlayOfMatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlayOfMatch[]    findAll()
 * @method PlayOfMatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlayOfMatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlayOfMatch::class);
    }

    /**
     * @param Challenge $challenge
     * @return PlayOfMatch[]
     */
    public function getPlayOfDataByChallenge(Challenge $challenge): array
    {
        return
            $this->createQueryBuilder('pom')
                ->join('pom.teamA', 'ta')
                ->join('ta.challengeDivision', 'cd')
                ->andWhere('cd.challenge = :challengeId')
                ->setParameter('challengeId', $challenge->getId())
                ->getQuery()
                ->getResult();
    }

    /**
     * @param PlayOfMatch[] $entities
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function massSave(array $entities)
    {
        foreach ($entities as $entity) {
            if (! $entity instanceof PlayOfMatch) {
                throw new RuntimeException(sprintf('batch save only for %s entity', PlayOfMatch::class));
            }
        }

        foreach ($entities as $entity) {
            $this->getEntityManager()->persist($entity);
        }
        $this->getEntityManager()->flush($entities);
    }

    // /**
    //  * @return PlayOfMatch[] Returns an array of PlayOfMatch objects
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
    public function findOneBySomeField($value): ?PlayOfMatch
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
