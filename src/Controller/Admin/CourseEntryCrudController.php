<?php

namespace App\Controller\Admin;

use App\Entity\CourseEntry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CourseEntryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CourseEntry::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('detail', fn (CourseEntry $entity) => "%entity_label_plural% $entity")
            ->setDefaultSort([
                'entryDate' => 'DESC',
            ])
            ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // ...
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('course')
            ->add('entryDate')
            ->add('plannedTeacher')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateField::new('entryDate')->setFormat('dd.MM.yyyy'),
            ChoiceField::new('entryTime'),
            TextField::new('course'),
            TextField::new('plannedTeacher'),
            TextField::new('plannedSubject'),
            TextField::new('plannedRoom'),
            AssociationField::new('scheduleType')->autocomplete(),
            TextField::new('updatedTeacher'),
            TextField::new('updatedSubject'),
            TextField::new('updatedRoom')->formatValue(fn ($value) => $value == null ?'-': $value),
            BooleanField::new('showComment')->renderAsSwitch(),
            TextareaField::new('message')->formatValue(fn ($value) => $value == null ?'-': $value)

        ];
    }

}
