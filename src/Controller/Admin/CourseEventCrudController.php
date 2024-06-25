<?php

namespace App\Controller\Admin;

use App\Entity\CourseEvent;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CourseEventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CourseEvent::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('course'),
            TextField::new('plannedRoom')->setRequired(true),
            ChoiceField::new('weekday'),
            ChoiceField::new('class'),
        ];
    }

}
