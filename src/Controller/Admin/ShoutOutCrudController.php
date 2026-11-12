<?php

namespace App\Controller\Admin;


use App\Entity\ShoutOut;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ShoutOutCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShoutOut::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('detail', fn (ShoutOut $entity) => "%entity_label_plural% $entity")
            ->setPageTitle('edit', fn (ShoutOut $entity) => "%entity_label_plural% $entity ändern")
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
            TextField::new('message')->setHelp('Maximal 5 bis 6 Wörter, bitte.')->setColumns('col-5'),
            AssociationField::new('type')->setRequired(true)->setColumns('col-3'),
            BooleanField::new('disabled')->setColumns('col-12'),
        ];
    }

}
