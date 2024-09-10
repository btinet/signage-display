<?php

namespace App\Controller\WebUntis;

use App\Entity\Block;
use App\Entity\CourseEntry;
use App\Form\SubstitutionsType;
use App\Repository\CourseEntryRepository;
use App\Repository\ScheduleGridRepository;
use App\Repository\ScheduleTypeRepository;
use App\Repository\WebUntisServerRepository;
use App\UntisModel\WebUntis;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/admin/webuntis', name: 'admin_untis_')]
class WebUntisController extends AbstractController
{


    /**
     * @throws TransportExceptionInterface
     * @throws \Exception
     */
    #[Route('/index', name: 'index')]
    public function index
    (
        Request $request,
        EntityManagerInterface $entityManager,
        HttpClientInterface $client,
        WebUntisServerRepository $serverRepository,
        ScheduleTypeRepository $scheduleType,
        ScheduleGridRepository $scheduleGrid,
        CourseEntryRepository $courseEntries
    ): Response
    {
        $substitutions = ['result' => null];
        $untis = new WebUntis($client,$serverRepository);
        $form = $this->createForm(SubstitutionsType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $startDate = $form->get('startDate')->getData();
            $endDate = $form->get('endDate')->getData();
            $simulate = $form->get('simulate')->getData();
            $deleteOldEntries = $form->get('delete_old_entries')->getData();

            if($untis->auth()) {
                $substitutions = $untis->getSubstitutions($startDate->format('Ymd'),$endDate->format('Ymd'));
                $untis->logout();
                if(array_key_exists('result', $substitutions) and !$simulate) {

                    // remove old data
                    if($deleteOldEntries) {
                        foreach($courseEntries->findAll() as $courseEntry) {
                            $entityManager->remove($courseEntry);
                        }
                    }

                    // create entry objects
                    foreach ($substitutions['result'] as $entry) {
                        $courseEntry = new CourseEntry();

                        // Type of Substitution
                        $type = $scheduleType->findOneBy(['webUntisType' => $entry['type']]);
                        if($type)
                            $courseEntry->setScheduleType($type);

                        // Date and Time Slot
                        $date = new DateTimeImmutable($entry['date']);
                        $courseEntry->setEntryDate($date);
                        $hour = $scheduleGrid->findOneBy(['startTime' => $entry['startTime']]);
                        $courseEntry->setEntryTime(Block::tryFrom($hour->getHour()));

                        // Teachers
                        $plannedTeacherArray = [];
                        $updatedTeacherArray = [];
                        foreach($entry['te'] as $teacher) {
                            if(isset($teacher['orgname'])) {
                                $plannedTeacherArray[] = $teacher['orgname'];
                                $updatedTeacherArray[] = $teacher['name'];
                            } else {
                                $plannedTeacherArray[] = $teacher['name'];
                            }
                        }
                        $courseEntry->setPlannedTeacher(implode(',', $plannedTeacherArray));
                        $courseEntry->setUpdatedTeacher(implode(',', $updatedTeacherArray));

                        // Klassen
                        $klassenArray = [];
                        foreach ($entry['kl'] as $klasse) {
                            $klassenArray[] = $klasse['name'];
                        }
                        $courseEntry->setCourse(implode(',', $klassenArray));

                        // Subject
                        $plannedSubjectArray = [];
                        $updatedSubjectArray = [];
                        foreach($entry['su'] as $subject) {
                            if(isset($subject['orgname'])) {
                                $plannedSubjectArray[] = $subject['orgname'];
                                $updatedSubjectArray[] = $subject['name'];
                            } else {
                                $plannedSubjectArray[] = $subject['name'];
                            }
                        }
                        $courseEntry->setPlannedSubject(implode(',', $plannedSubjectArray));
                        $courseEntry->setUpdatedSubject(implode(',', $updatedSubjectArray));

                        // Room
                        $plannedRoomArray = [];
                        $updatedRoomArray = [];
                        foreach($entry['ro'] as $room) {
                            if(isset($room['orgname'])) {
                                $plannedRoomArray[] = $room['orgname'];
                                $updatedRoomArray[] = $room['name'];
                            } else {
                                $plannedRoomArray[] = $room['name'];
                            }
                        }
                        $courseEntry->setPlannedRoom(implode(',', $plannedRoomArray));
                        $courseEntry->setUpdatedRoom(implode(',', $updatedRoomArray));

                        // Extra Text
                        if(isset($entry['txt'])) {
                            $courseEntry->setMessage($entry['txt']);
                        }

                        // Default to disable Message Text
                        $courseEntry->setShowComment(false);

                        $entityManager->persist($courseEntry);
                    }
                    $entityManager->flush();
                    $this->addFlash('warning','Daten wurden importiert.');
                }
            } else {
                $this->addFlash('danger','Verbindung zu WebUntis fehlgeschlagen.');
            }
        }


        return $this->render('admin/untis/index.html.twig', [
            'response' => $substitutions['result'],
            'form' => $form->createView(),
        ]);
    }

}