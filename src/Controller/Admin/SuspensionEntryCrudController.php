<?php

namespace App\Controller\Admin;

use App\Entity\CourseEntry;
use App\Entity\ScheduleType;
use App\Entity\SuspensionEntry;
use App\Repository\CourseEntryRepository;
use App\Repository\ScheduleTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;

class SuspensionEntryCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return SuspensionEntry::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('detail', fn (SuspensionEntry $entity) => "%entity_label_plural% $entity")
            ->setPageTitle('edit', fn (SuspensionEntry $entity) => "%entity_label_plural% $entity ändern")
            ->showEntityActionsInlined()
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('teacher'),
            DateField::new('startDate'),
            DateField::new('endDate'),
            BooleanField::new('disabled')
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $courseEntryRepository = $entityManager->getRepository(CourseEntry::class);
        $scheduleTypeRepository = $entityManager->getRepository(ScheduleType::class);

        $startDate = clone $entityInstance->getStartDate();
        $endDate = $entityInstance->getEndDate();
        $teacher = $entityInstance->getTeacher();
        $scheduleType = $scheduleTypeRepository->findOneBy([
            'slug' => 'ausfall'
        ]);

        $courseEntries = $courseEntryRepository->findBy([
            'plannedTeacher' => $teacher,
            'scheduleType' => null
        ]);

        $dateInterval = $startDate->diff($endDate);

        for ($i = 1 ;$i <= $dateInterval->days;$i++) {

            foreach ($courseEntries as $courseEntry) {
                if($courseEntry->getEntryDate()->format('Y-m-d') == $startDate->format('Y-m-d')) {
                    $courseEntry->setScheduleType($scheduleType);
                    $entityManager->persist($courseEntry);
                }
            }
            $startDate = date_add($startDate,date_interval_create_from_date_string("1 day"));
        }
        $entityManager->flush();

        parent::persistEntity($entityManager, $entityInstance);
    }

}
