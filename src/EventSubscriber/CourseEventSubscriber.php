<?php

namespace App\EventSubscriber;

use App\Entity\CourseEntry;
use App\Entity\CourseEvent;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CourseEventSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['propagateCourseEvents'],
        ];
    }

    public function propagateCourseEvents(BeforeEntityPersistedEvent $event) {

        $entity = $event->getEntityInstance();

        if ($entity instanceof CourseEvent) {
            $course = $entity->getCourse();
            $plannedRoom = $entity->getPlannedRoom();
            $startDate = $entity->getCourse()->getStartDate();
            $endDate = $entity->getCourse()->getEndDate();
            $teacher = $entity->getCourse()->getTeacher();
            $entryDay = $entity->getWeekday();
            $entryTime = $entity->getClass();
            $dateInterval = $startDate->diff($endDate);
            for ($i = 1; $i <= $dateInterval->days; $i++) {
                $currentDate = date_add($startDate, date_interval_create_from_date_string("1 day"));
                if ($entryDay->value == $currentDate->format("w")) {
                    $courseEntry = new CourseEntry();
                    $courseEntry->setEntryDate($currentDate);
                    $courseEntry->setEntryTime($entryTime);
                    $courseEntry->setCourse($course);
                    $courseEntry->setPlannedRoom($plannedRoom);
                    $courseEntry->setPlannedTeacher($teacher);
                    $this->entityManager->persist($courseEntry);
                    $this->entityManager->flush();
                }
            }
        }

    }

}
