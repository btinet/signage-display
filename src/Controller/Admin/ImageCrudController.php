<?php

namespace App\Controller\Admin;

//use App\Entity\Image;
use App\Entity\Image;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Image as ImageConstraint;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('showCaption'),
            TextField::new('title'),
            TextareaField::new('description'),
            ImageField::new('file')
                ->setBasePath('images/uploads')
                ->setUploadDir('public/images/uploads')
                ->setFileConstraints(new ImageConstraint( maxRatio: 1.5, minRatio: 0.66))
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]'),
            BooleanField::new('disabled')
        ];
    }

}
