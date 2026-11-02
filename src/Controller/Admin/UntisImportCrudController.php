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
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\FileUploadType;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DomCrawler\Field\FileFormField;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints\File;

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
            ->disable(Action::SAVE_AND_ADD_ANOTHER, Action::EDIT);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setHelp('new', 'Klassen/Kurse, die den Schlüsselwörtern der Ausschlussliste entsprechen, werden beim Import ignoriert.')
            ->overrideTemplates([
                'crud/new' => 'admin/pages/edit.html.twig',
            ]);
    }


    public function configureFields(string $pageName): iterable
    {

        $value = <<<DATA
        Klassen/Kurse, die den Schlüsselwörtern der Ausschlussliste
        entsprechen, werden beim Import ignoriert.
        DATA;

        return [
            ImageField::new('filename', 'GPUxxx.txt aus DIF-Export')
                ->setFormType(FileUploadType::class)
                ->setFormTypeOptions([
                ])
                ->setCustomOption('basePath', 'uploads/files')
                ->setCustomOption('uploadDir', 'public/uploads/files')
                ->setFormTypeOptions(['attr' => [
                    'accept' => 'text/plain'
                ]

                ])
                ->setFileConstraints(new File([
                        'mimeTypes' => [
                            'text/plain',
                            'text/x-csv',
                            'text/csv',
                            'application/vnd.ms-excel',
                            'application/csv',
                            'application/x-csv',
                        ]
                    ])
                )
                ->setRequired(true)
                ->setHelp("Nach upload werden alte Datensätze entfernt."),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {

        $filters = $this->filterRepository->findAll();

        foreach ($this->courseEntryRepository->findAll() as $courseEntry) {
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
        if ($isANSI = mb_detect_encoding($file, 'ANSI_X3.4-1986', true)) {
            $this->addFlash("warning", "Datei ist nicht UTF-8-formatiert. Der Import wurde mit ISO-8859-1 versucht.");
        }

        if (($handle = fopen($this->getParameter('upload_directory') . '/' . $file, "r")) !== FALSE) {
            try {
                while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                    $isNotFiltered = true;
                    $untisId = trim($data[0]);
                    $dateString = trim($data[1]);
                    $date = new DateTimeImmutable($dateString);
                    $courseHour = (int)trim($data[2]);
                    $absenceId = (int)trim($data[3]);
                    $courseId = (int)trim($data[4]);
                    $plannedTeacher = trim($data[5]);
                    $updatedTeacher = trim($data[6]);
                    $plannedSubject = trim($data[7]);
                    $plannedSubjectComment = trim($data[8]);
                    $updatedSubject = trim($data[9]);
                    $updatedSubjectComment = trim($data[10]);
                    $plannedRoom = str_replace('~', ', ', trim($data[11]));
                    $updatedRoom = str_replace('~', ', ', trim($data[12]));
                    $statistics = trim($data[13]);
                    $classes = str_replace('~', ', ', trim($data[14]));
                    $absenceReason = trim($data[15]);
                    $message = trim($data[16]);
                    $bit = trim($data[17]);
                    $updatedClasses = str_replace('~', ', ', trim($data[18]));
                    $type = trim($data[19]);

                    foreach ($filters as $filter) {
                        if (str_contains($classes, $filter->getKeyword()) || empty($classes)) {
                            $isNotFiltered = false;
                            break;
                        }
                    }
                    if ($isNotFiltered) {
                        $entry = new CourseEntry();
                        $entry->setCourse($classes);
                        $entry->setUpdatedCourse($updatedClasses);
                        $entry->setMessage($message);
                        $entry->setPlannedTeacher($plannedTeacher);
                        $entry->setUpdatedTeacher($updatedTeacher);
                        $code = 1;
                        $isSubstitution = (trim($type) === 'S' || (trim($type) === '' and $updatedTeacher !== '') || trim($type) === '~') ||
                            trim($type) === 'A' && ($plannedTeacher !== '' and $updatedTeacher !== '' and $plannedTeacher !== $updatedTeacher and $updatedSubject !== ''); // Substitution
                        $isCancellation = trim($type) === 'C' || (trim($type) === '' and $updatedTeacher === '');; // Cancellation
                        $isPropagation  = trim($type) === 'P';
                        if ($isSubstitution || $isPropagation) {
                            $code = 0;
                        }
                        if ($isCancellation) {
                            $code = 1;
                        }
                        if (str_contains($entry->getMessage(), "usfall")) $code = 1;
                        if (str_contains($entry->getMessage(), "entfall")) $code = 1;

                        $type = $this->typeRepository->findOneBy(['code' => $code]);
                        $entry->setScheduleType($type);

                        if ($entry->getMessage())
                            $entry->setShowComment(true);
                        else
                            $entry->setShowComment(false);
                        $entry->setEntryDate($date);
                        $entry->setEntryTime(Block::tryFrom($courseHour));
                        $entry->setPlannedRoom($plannedRoom);
                        $entry->setUpdatedRoom($updatedRoom);
                        $entry->setPlannedSubject($plannedSubject);
                        $entry->setUpdatedSubject($updatedSubject);
                        $entityManager->persist($entry);
                    }

                }
                $entityManager->flush();
                $this->addFlash("success", "Die Stundenplanung wurde erfolgreich aktualisiert.");
            } catch (\Exception $exception) {
            }
        }
    }


    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function getRedirectResponseAfterSave(AdminContext $context, string $action): RedirectResponse
    {

        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);


        $url = $adminUrlGenerator
            ->setController(CourseEntryCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        return $this->redirect($url);
    }

}
