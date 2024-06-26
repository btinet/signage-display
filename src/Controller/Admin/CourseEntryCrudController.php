<?php

namespace App\Controller\Admin;

use App\Entity\CourseEntry;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
            AssociationField::new('course')->autocomplete(),
            DateField::new('entryDate')->setFormat('dd.MM.yyyy'),
            ChoiceField::new('entryTime'),
            AssociationField::new('plannedTeacher')->autocomplete(),
            TextField::new('plannedRoom'),
            AssociationField::new('scheduleType')->autocomplete(),
            AssociationField::new('updatedTeacher')->autocomplete(),
            TextField::new('updatedRoom')->formatValue(fn ($value) => $value == null ?'-': $value),
            TextareaField::new('message')->formatValue(fn ($value) => $value == null ?'-': $value)

        ];
    }

}
