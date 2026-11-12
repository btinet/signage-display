<?php

namespace App\Controller\Admin;

use App\Entity\DisplayMacro;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DisplayMacroCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DisplayMacro::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Allgemein'),
            TextField::new('title'),
            DateField::new('validFrom', 'Start Date'),
            DateField::new('vaildTo', 'End Date'),
            BooleanField::new('showScheduleColumn'),
            BooleanField::new('active'),

            FormField::addTab('Beiträge'),
            AssociationField::new('displayBlogPosts', 'Aktive Beiträge')->setFormTypeOptions([
                'expanded' => true,
            ]),

            FormField::addTab('Ticker'),
            AssociationField::new('displayTickers', 'Aktive Ticker')->setFormTypeOptions([
                'expanded' => true,
            ]),
        ];
    }

}
