<?php

namespace App\Controller\Admin;

use App\Entity\ClassGroup;
use App\Entity\Course;
use App\Repository\CourseRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ClassGroupCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ClassGroup::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('detail', fn (ClassGroup $entity) => "%entity_label_plural% $entity")
            ->setPageTitle('edit', fn (ClassGroup $entity) => "%entity_label_plural% $entity ändern")
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
            FormField::addTab('courses attached')->hideOnForm(),
            CollectionField::new('courses',false)
                ->hideOnForm()
                ->setTemplatePath('admin/components/table.html.twig'),
            FormField::addTab('course events')->onlyOnDetail(),
            CollectionField::new('courses',false)
                ->setTemplatePath('admin/components/class_event_table.html.twig')
                ->onlyOnDetail(),
            FormField::addTab('events')->hideOnForm(),
            CollectionField::new('courses',false)
                ->setTemplatePath('admin/components/class_suspension_table.html.twig')
                ->onlyOnDetail(),
            FormField::addTab('students')->hideOnForm(),


            FormField::addTab('general'),
            FormField::addFieldset('Allgemein'),
            TextField::new('fullLabel')->setColumns(6)->onlyOnIndex(),
            TextField::new('label')->setColumns(6)->hideOnIndex(),
            AssociationField::new('teacher')->setColumns(6),
            DateField::new('startDate')->setColumns(3)->setRequired(true),
            DateField::new('endDate')->setColumns(3)->setRequired(true),
            AssociationField::new('courses')
                ->hideOnDetail()
                ->setQueryBuilder(function (QueryBuilder $qb) {
                    $qb
                        ->andWhere('entity.startDate >= :startDate')
                        ->andWhere('entity.startDate <= :endDate')
                        ->andWhere('entity.endDate >= :now')
                        ->setParameter('startDate', $this->getContext()->getEntity()->getInstance()->getStartDate())
                        ->setParameter('endDate', $this->getContext()->getEntity()->getInstance()->getEndDate())
                        ->setParameter('now', (new \DateTime())->format('Y-m-d'));
                })
                ->setFormTypeOptions([
                    'by_reference' => false,
                    'expanded' => false
                ])
            ->setHelp('Kurse können erst nach Speichern des Gültigkeitszeitraums zugeordnet werden.')
            ,

            FormField::addFieldset('Infos'),
            TextareaField::new('description'),
        ];
    }

}
