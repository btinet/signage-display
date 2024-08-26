<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Validator\Constraints\Image as ImageConstraint;

class BlogPostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BlogPost::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Allgemein')->onlyOnForms(),
            BooleanField::new('active'),
            IntegerField::new('duration')
            ->setHelp("Beitragsdauer in Sekunden."),
            IdField::new('id')->hideOnForm(),
            AssociationField::new('template')->setRequired(true)->setColumns(3),
            TextField::new('title')->setColumns(9),
            BooleanField::new('titleVisible'),
            TextEditorField::new('content')
                ->setColumns(12),
            BooleanField::new('contentVisible'),
            ImageField::new('featuredImage')
                ->setBasePath('posts/uploads')
                ->setUploadDir('public/posts/uploads')
                ->setFileConstraints(new ImageConstraint(maxSize: '2048k'))
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]'),
            BooleanField::new('featuredImageVisible'),
            FormField::addTab('Listen')->onlyOnForms(),
            CollectionField::new('list')
                ->setEntryIsComplex(true)
                ->useEntryCrudForm(ListEntryCrudController::class),

            FormField::addTab('Bildergalerie')->onlyOnForms(),
            AssociationField::new('gallery')->setFormTypeOptions([
                'expanded' => true
            ]),
            FormField::addTab('Vertretungsplan')->onlyOnForms(),
            TextField::new('scheduleOffset')->setHelp('z.B. "+1 day" oder "+2 days"'),
        ];
    }

}
