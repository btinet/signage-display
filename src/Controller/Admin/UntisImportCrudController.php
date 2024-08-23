<?php

namespace App\Controller\Admin;

use App\Entity\Block;
use App\Entity\CourseEntry;
use App\Entity\UntisImport;
use App\Repository\ScheduleTypeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UntisImportCrudController extends AbstractCrudController
{

    private ScheduleTypeRepository $typeRepository;

    public function __construct(ScheduleTypeRepository $typeRepository)
    {
        $this->typeRepository = $typeRepository;
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
        return [
            TextField::new('filename','GPU-Datei')
                ->setFormType(FileUploadType::class)
                ->setFormTypeOptions([
                ])
                ->setCustomOption('basePath', 'uploads/files')
                ->setCustomOption('uploadDir', 'public/uploads/files')
                ->setRequired(true)
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $fileTypes = [
            'text/plain',
            'text/x-csv',
            'text/csv',
            'application/vnd.ms-excel',
            'application/csv',
            'application/x-csv',
        ];

        $file = $entityInstance->getFilename();

        if (($handle = fopen($this->getParameter('upload_directory') . '/' . $file, "r")) !== FALSE) {
            try {
                while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                    $date = new DateTimeImmutable($data[1]);
                    $entry = new CourseEntry();
                    $courses = str_replace("~", ", ",$data[14]);
                    $entry->setCourse($courses);
                    $entry->setMessage(iconv('ISO-8859-1', 'UTF-8', $data[16]));
                    $entry->setPlannedTeacher(iconv('ISO-8859-1', 'UTF-8', $data[5]));
                    $entry->setUpdatedTeacher(iconv('ISO-8859-1', 'UTF-8', $data[6]));

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
                    $entry->setPlannedRoom($data[11]);
                    $entry->setUpdatedRoom($data[12]);
                    $entry->setPlannedSubject($data[7]);
                    $entry->setUpdatedSubject($data[9]);

                    $entityManager->persist($entry);
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
