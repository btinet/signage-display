<?php

namespace App\Repository;

use App\Entity\CourseEntry;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CourseEntry>
 */
class CourseEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourseEntry::class);
    }

    /**
     * @return CourseEntry[] Returns an array of CourseEntry objects
     */
    public function findCurrentEntries(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.scheduleType IS NOT NULL')
            ->andWhere('c.entryDate = :nowDate')
            ->setParameter('nowDate', date('Y-m-d'))
            ->orderBy('c.entryTime', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return CourseEntry[] Returns an array of CourseEntry objects
     */
    public function findEntriesAt(string $interval = "+1 day"): array
    {
        $date = new DateTime();
        $date->modify($interval);

        return $this->createQueryBuilder('c')
            ->andWhere('c.scheduleType IS NOT NULL')
            ->andWhere('c.entryDate = :nowDate')
            ->setParameter('nowDate', $date->format('Y-m-d'))
            ->orderBy('c.entryTime', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

//    public function findOneBySomeField($value): ?CourseEntry
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
