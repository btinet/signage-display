<?php

namespace App\Repository;

use App\Entity\Course;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Course>
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    /**
    * @return Course[] Returns an array of Course objects
    */
    public function findAllAttachable(\DateTime $classYear): array
    {
       return $this->createQueryBuilder('c')
           ->andWhere('c.startDate = :classYear')
           //->andWhere('c.endDate <= :nowDate')
           ->setParameter('classYear', $classYear->format('Y-m-d'))
           //->setParameter('nowDate', (new DateTime())->format('Y-m-d'))
           ->orderBy('c.id', 'ASC')
           ->getQuery()
           ->getResult()
       ;
    }

//    public function findOneBySomeField($value): ?Course
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
