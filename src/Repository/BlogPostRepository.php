<?php

namespace App\Repository;

use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BlogPost>
 */
class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

   /**
    * @return BlogPost[] Returns an array of BlogPost objects
    */
   public function findCurrentIntervalBlogPosts(): array
   {
       $now = date("Y-m-d");
       return $this->createQueryBuilder('b')
           ->andWhere('b.active = true')
           ->andWhere('b.startDate <= :now')
           ->andWhere('b.endDate >= :now')
           ->setParameter("now", $now)
           ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

    /**
     * @return BlogPost[] Returns an array of BlogPost objects
     */
    public function findCurrentDateBlogPosts(): array
    {
        $now = date("Y-m-d");
        return $this->createQueryBuilder('b')
            ->andWhere('b.active = true')
            ->andWhere('b.startDate = :now')
            ->andWhere('b.endDate IS NULL')
            ->setParameter("now", $now)
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return BlogPost[] Returns an array of BlogPost objects
     */
    public function findCurrentBlogPosts(): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.active = true')
            ->andWhere('b.startDate IS NULL')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

//    public function findOneBySomeField($value): ?BlogPost
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
