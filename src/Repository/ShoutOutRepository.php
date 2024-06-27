<?php

namespace App\Repository;

use App\Entity\ShoutOut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ShoutOut>
 */
class ShoutOutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShoutOut::class);
    }

    /**
     * @return ShoutOut[] Returns an array of CourseEntry objects
     */
    public function findCurrentEntries(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.disabled IS NOT NULL')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return ShoutOut[] Returns an array of ShoutOut objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ShoutOut
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
