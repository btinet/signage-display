<?php

namespace App\Controller\Admin;

use App\Entity\ListEntry;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ListEntryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ListEntry::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('description')->setColumns(6),
            TextField::new('content')->setColumns(6),
        ];
    }

}
