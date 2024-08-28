<?php

namespace App\Controller\Admin;

use App\Entity\CourseEntryFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CourseEntryFilterCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CourseEntryFilter::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('keyword'),
        ];
    }

}
