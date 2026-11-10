<?php

namespace App\Controller\Admin;

use App\Entity\Block;
use App\Entity\CourseEntry;
use App\Entity\UntisImport;
use App\Repository\CourseEntryFilterRepository;
use App\Repository\CourseEntryRepository;
use App\Repository\ScheduleTypeRepository;
use Btinet\Untis\Model\UntisSubstitutionModel;
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
            ImageField::new('filename', 'GPUxxx.txt aus DIF-Export') // TODO: soll vor der Weiterverarbeitung in lowerCases() sein. also: gpu014.txt
                ->setFormType(FileUploadType::class)
                ->setFormTypeOptions([
                ])
                ->setCustomOption('basePath', 'uploads/files')
                ->setCustomOption('uploadDir', 'public/uploads/files')
                ->setFormTypeOptions(['attr' => [
                    'accept' => 'text/plain'
                ]

                ])
                ->setUploadedFileNamePattern('_empty.data') // TODO: soll immer "gpu014.txt" sein
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
        $entityManager->flush();
        $file = $entityInstance->getFilename();

        if (($handle = fopen($this->getParameter('upload_directory') . '/' . $file, "r")) !== FALSE) {
            try {
                $model = new UntisSubstitutionModel();
                while (($data = fgetcsv($handle, 10000, ",")) !== FALSE) {
                    $model->setData($data);

                    foreach ($filters as $filter) {
                        if (in_array($filter->getKeyword(), $model->getClasses()) || empty($model->getPlannedSubject()) || empty($model->getClasses())) {
                            continue 2;
                        }
                    }
                    $code = 1;
                    $displayEntry = $model->getSplitter()->getDisplayEntry();
                    $plannedClasses = $model->getClasses();

                    foreach ($plannedClasses as $class) {
                        $entry = new CourseEntry();
                        $hasUpdatedClass = array_search($class, $model->getUpdatedClasses());

                        $entry->setCourse($class);
                        $entry->setMessage($model->getMessage());
                        $entry->setPlannedTeacher($model->getPlannedTeacher());
                        $entry->setUpdatedTeacher($model->getUpdatedTeacher());

                        if ($hasUpdatedClass) {
                            $entry->setUpdatedCourse($model->getUpdatedClasses()[$hasUpdatedClass]);
                        }
                        if ($displayEntry->isSubstituted()) {
                            $code = 0;
                        }
                        if ($displayEntry->isCancelled()) {
                            $code = 1;
                        }
                        if ($displayEntry->isMoved()) {
                            $code = 302;
                        }
                        $type = $this->typeRepository->findOneBy(['code' => $code]);
                        $entry->setScheduleType($type);

                        if ($entry->getMessage())
                            $entry->setShowComment(true);
                        else
                            $entry->setShowComment(false);
                        $entry->setEntryDate($model->getDate());
                        $entry->setEntryTime(Block::tryFrom($model->getCourseHour()));
                        $entry->setPlannedRoom($model->getPlannedRoom());
                        $entry->setUpdatedRoom($model->getUpdatedRoom());
                        $entry->setPlannedSubject($model->getPlannedSubject());
                        $entry->setUpdatedSubject($model->getUpdatedSubject());
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
