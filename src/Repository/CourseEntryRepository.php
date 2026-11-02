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

    public function findCurrentEntries(): array
    {
        // 1. Query ausführen
        $results = $this->createQueryBuilder('c')
            ->andWhere('c.scheduleType IS NOT NULL')
            ->andWhere('c.entryDate = :nowDate')
            ->setParameter('nowDate', date('Y-m-d'))
            ->orderBy('c.course', 'ASC')
            ->addOrderBy('c.entryTime', 'ASC')
            ->setMaxResults(24)
            ->getQuery()
            ->getResult();

        // 2. Gruppieren nach Klasse (Course)
        $grouped = [];
        foreach ($results as $entry) {
            // Annahme: Der Getter heißt getCourse()
            $grouped[$entry->getCourse()][] = $entry;
        }

        return $grouped;
    }

    public function findEntriesAt(string $interval = "+1 day"): array
    {
        $date = new \DateTime();

        // Falls heute Freitag ist, dann Daten für Montag holen
        if ($date->format('w') == 5) {
            $interval = "+3 day";
        }
        // Falls heute Samstag ist, dann Daten für Montag holen
        if ($date->format('w') == 6) {
            $interval = "+2 day";
        }
        $date->modify($interval);

        // 1. Die flache Liste aus der Datenbank holen
        $results = $this->createQueryBuilder('c')
            ->andWhere('c.scheduleType IS NOT NULL')
            ->andWhere('c.entryDate = :nowDate')
            ->setParameter('nowDate', $date->format('Y-m-d'))
            ->orderBy('c.course', 'ASC')
            ->addOrderBy('c.entryTime', 'ASC')
            ->setMaxResults(48)
            ->getQuery()
            ->getResult();

        // 2. Ergebnisse nach Klasse (course) gruppieren
        $grouped = [];
        foreach ($results as $entry) {
            // Annahme: Der Getter in deiner Entity heißt getCourse()
            $courseName = $entry->getCourse();

            // Füge den Entry dem Array unter dem Klassennamen hinzu
            $grouped[$courseName][] = $entry;
        }

        return $grouped;
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
