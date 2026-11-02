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
            ->setMaxResults(100)
            ->getQuery()
            ->getResult();

        // 2. Gruppieren nach Klasse (Course)
        $grouped = [];
        foreach ($results as $entry) {
            // Annahme: Der Getter heißt getCourse()
            $grouped[$entry->getCourse()][] = $entry;
        }

        // 3. Natürliche Sortierung der Klassen
        uksort($grouped, function($a, $b) {
            // Extrahiere Zahl und Rest (z.B. "10A" → [10, "A"], "7.1" → [7, ".1"])
            preg_match('/^(\d+)(.*)$/', $a, $matchA);
            preg_match('/^(\d+)(.*)$/', $b, $matchB);

            $numA = (int)($matchA[1] ?? 0);
            $numB = (int)($matchB[1] ?? 0);
            $restA = $matchA[2] ?? '';
            $restB = $matchB[2] ?? '';

            // Zuerst nach Klassenstufe sortieren
            if ($numA !== $numB) {
                return $numA <=> $numB;
            }

            // Dann nach Buchstabe/Rest sortieren
            return strcmp($restA, $restB);
        });

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
            ->setMaxResults(100)
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

        // 3. Natürliche Sortierung der Klassen
        uksort($grouped, function($a, $b) {
            // Extrahiere Zahl und Rest (z.B. "10A" → [10, "A"], "7.1" → [7, ".1"])
            preg_match('/^(\d+)(.*)$/', $a, $matchA);
            preg_match('/^(\d+)(.*)$/', $b, $matchB);

            $numA = (int)($matchA[1] ?? 0);
            $numB = (int)($matchB[1] ?? 0);
            $restA = $matchA[2] ?? '';
            $restB = $matchB[2] ?? '';

            // Zuerst nach Klassenstufe sortieren
            if ($numA !== $numB) {
                return $numA <=> $numB;
            }

            // Dann nach Buchstabe/Rest sortieren
            return strcmp($restA, $restB);
        });

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
