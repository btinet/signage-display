<?php

namespace App\Controller\Admin;

use App\Entity\ClassGroup;
use App\Entity\Course;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CourseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Course::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('detail', fn (Course $entity) => "%entity_label_plural% $entity")
            ->setPageTitle('edit', fn (Course $entity) => "%entity_label_plural% $entity ändern")
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
            FormField::addTab('Veranstaltungen')->onlyOnDetail(),
            CollectionField::new('courseEvents',false)
                ->setTemplatePath('admin/components/course_event_table.html.twig')
                ->onlyOnDetail(),
            FormField::addTab('Allgemein'),
            FormField::addFieldset('Allgemein'),
            TextField::new('label')->setColumns(6),
            TextField::new('internLabel')->setColumns(6),
            DateField::new('startDate')->setRequired(true)->setColumns(3),
            DateField::new('endDate')->setRequired(true)->setColumns(3),
            AssociationField::new('subject')
                ->setQueryBuilder(function (QueryBuilder $qb) {
                    $qb
                        ->orderBy('entity.label','asc')
                    ;
                })
                ->setRequired(true)->setColumns(6),
            AssociationField::new('teacher')
                ->setQueryBuilder(function (QueryBuilder $qb) {
                    $qb
                        ->orderBy('entity.firstname, entity.lastname')
                    ;
                })
                ->setRequired(true)->setColumns(6),
            AssociationField::new('classGroup')
                ->onlyOnForms()
                ->setQueryBuilder(function (QueryBuilder $qb) {
                    $qb
                        ->andWhere('entity.startDate <= :startDate')
                        ->andWhere('entity.endDate >= :startDate')
                        ->andWhere('entity.endDate >= :now')
                        ->setParameter('startDate', $this->getContext()->getEntity()->getInstance()->getStartDate())
                        ->setParameter('now', (new \DateTime())->format('Y-m-d'));
                })
                ->setHelp('Klassen können erst nach Speichern des Gültigkeitszeitraums zugeordnet werden.')
            ->setColumns(6),

            BooleanField::new('extended')->setColumns(12),

            CollectionField::new('classGroup')->hideOnForm(),


            FormField::addFieldset('Info'),
            TextareaField::new('description',false)->hideOnIndex(),
        ];
    }
}
