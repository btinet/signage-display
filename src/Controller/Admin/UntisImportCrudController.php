<?php

namespace App\Controller\Admin;

use App\Entity\Block;
use App\Entity\CourseEntry;
use App\Entity\UntisImport;
use App\Repository\CourseEntryFilterRepository;
use App\Repository\CourseEntryRepository;
use App\Repository\ScheduleTypeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UntisImportCrudController extends AbstractCrudController
{

    private ScheduleTypeRepository $typeRepository;
    private CourseEntryRepository $courseEntryRepository;
    private CourseEntryFilterRepository $filterRepository;

    public function __construct(ScheduleTypeRepository $typeRepository, CourseEntryRepository $courseEntryRepository, CourseEntryFilterRepository $filterRepository)
    {
        $this->typeRepository = $typeRepository;
        $this->courseEntryRepository = $courseEntryRepository;
        $this->filterRepository = $filterRepository;
    }

    public static function getEntityFqcn(): string
    {
        return UntisImport::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            // this will forbid to create or delete entities in the backend
            ->disable(Action::SAVE_AND_ADD_ANOTHER, Action::EDIT)
            ;
    }


    public function configureFields(string $pageName): iterable
    {

        $value = <<<DATA
        Klassen/Kurse, die den Schlüsselwörtern der Ausschlussliste
        entsprechen, werden beim Import ignoriert.
        DATA;

        return [
            TextField::new('filename','GPUxxx.txt aus DIP-Export')
                ->setFormType(FileUploadType::class)
                ->setFormTypeOptions([
                ])
                ->setCustomOption('basePath', 'uploads/files')
                ->setCustomOption('uploadDir', 'public/uploads/files')
                ->setRequired(true)
                ->setHelp("Nach upload werden alte Datensätze entfernt. (Dateiname wird im Formular nicht angezeigt, nicht verwirren lassen.)"),
            TextareaField::new('info')
            ->setRequired(false)
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        $filters = $this->filterRepository->findAll();

        foreach($this->courseEntryRepository->findAll() as $courseEntry) {
            $entityManager->remove($courseEntry);
        }
        $entityManager->flush();;

        $fileTypes = [
            'text/plain',
            'text/x-csv',
            'text/csv',
            'application/vnd.ms-excel',
            'application/csv',
            'application/x-csv',
        ];

        $file = $entityInstance->getFilename();
        if(!mb_detect_encoding($file, 'UTF-8', true)) {
            $this->addFlash("warning","Datei ist nicht UTF-8-formatiert. Der Import wurde mit ISO-8859-1 versucht.");
        }

        if (($handle = fopen($this->getParameter('upload_directory') . '/' . $file, "r")) !== FALSE) {
            try {
                while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                    $isNotFiltered = true;
                    $date = new DateTimeImmutable($data[1]);
                    $courses = str_replace("~", ", ",$data[14]);
                    $plannedRooms = str_replace("~", ", ",$data[11]);
                    $updatedRooms = str_replace("~", ", ",$data[12]);

                    foreach ($filters as $filter) {
                        if(str_contains($courses, $filter->getKeyword()) || empty($courses)) {
                            $isNotFiltered = false;
                            break;
                        }
                    }
                    if($isNotFiltered) {
                        $entry = new CourseEntry();
                        $entry->setCourse($courses);

                        if(mb_detect_encoding($file, 'UTF-8', true)) {
                            $entry->setMessage($data[16]);
                            $entry->setPlannedTeacher($data[5]);
                            $entry->setUpdatedTeacher( $data[6]);
                        } else {
                            $entry->setMessage(iconv('ISO-8859-1', 'UTF-8', $data[16]));
                            $entry->setPlannedTeacher(iconv('ISO-8859-1', 'UTF-8', $data[5]));
                            $entry->setUpdatedTeacher(iconv('ISO-8859-1', 'UTF-8', $data[6]));
                        }


                        $code = $data[17];
                        //if($code == "2") $code = 1;
                        if($code == "1048576") $code = 0;
                        if($code == "1048577") $code = 1;
                        if($code == "1") $code = 1;
                        if($entry->getUpdatedTeacher()) $code = 0;

                        $type = $this->typeRepository->findOneBy(['code' => $code]);
                        $entry->setScheduleType($type);

                        $entry->setEntryDate($date);
                        $entry->setEntryTime(Block::tryFrom($data[2]));
                        $entry->setPlannedRoom($plannedRooms);
                        $entry->setUpdatedRoom($updatedRooms);
                        $entry->setPlannedSubject($data[7]);
                        $entry->setUpdatedSubject($data[9]);

                        $entityManager->persist($entry);
                    }

                }
                $entityManager->flush();
                $this->addFlash("success","Die Stundenplanung wurde erfolgreich aktualisiert.");
            } catch (\Exception $exception) {
            }
        }
    }


    protected function getRedirectResponseAfterSave(AdminContext $context, string $action): RedirectResponse
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);


            $url = $adminUrlGenerator
                ->setController(CourseEntryCrudController::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
            ;

            return $this->redirect($url);
    }

}
