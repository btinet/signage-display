<?php

namespace App\Controller\Admin;

use App\Entity\ImageGallery;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ImageGalleryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ImageGallery::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextareaField::new('description'),
            BooleanField::new('disabled'),
            DateField::new('startDate'),
            DateField::new('endDate'),
            AssociationField::new('images')->setFormTypeOptions([
                'by_reference' => false
            ])
        ];
    }

}
