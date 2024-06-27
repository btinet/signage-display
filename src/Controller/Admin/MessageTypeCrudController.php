<?php

namespace App\Controller\Admin;

use App\Entity\MessageType;
use App\Entity\ShoutOut;
use Doctrine\DBAL\Types\TextType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MessageTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return MessageType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('detail', fn (MessageType $entity) => "%entity_label_plural% $entity")
            ->setPageTitle('edit', fn (MessageType $entity) => "%entity_label_plural% $entity ändern")
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
            TextField::new('label'),
            TextField::new('cssClass'),
        ];
    }

}
