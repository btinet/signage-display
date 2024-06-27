<?php

namespace App\Repository;

use App\Entity\ImageGallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImageGallery>
 */
class ImageGalleryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImageGallery::class);
    }

    /**
     * @return ImageGallery[] Returns an array of CourseEntry objects
     */
    public function findActiveEntries(): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.disabled IS NOT NULL')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

//    /**
//     * @return ImageGallery[] Returns an array of ImageGallery objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ImageGallery
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
