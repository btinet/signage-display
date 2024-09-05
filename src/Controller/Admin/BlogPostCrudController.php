<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
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

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            // ...
            ->showEntityActionsInlined()
            ;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Allgemein')->onlyOnForms(),
            BooleanField::new('active'),
            DateField::new('startDate')->setColumns(2),
            DateField::new('endDate')->setColumns(2),
            IntegerField::new('duration')->setColumns(8)->onlyOnForms()
            ->setHelp("Beitragsdauer in Sekunden (Standard sind 10 Sekunden)."),
            AssociationField::new('template')->setRequired(true)->setColumns(3),
            TextField::new('title')->setColumns(9),
            BooleanField::new('titleVisible')->onlyOnForms(),
            TextEditorField::new('content')->onlyOnForms()
                ->setColumns(12),
            BooleanField::new('contentVisible')->onlyOnForms(),
            ImageField::new('featuredImage')->onlyOnForms()
                ->setBasePath('posts/uploads')
                ->setUploadDir('public/posts/uploads')
                ->setFileConstraints(new ImageConstraint(maxSize: '2048k'))
                ->setUploadedFileNamePattern('[slug]-[timestamp].[extension]'),
            BooleanField::new('featuredImageVisible')->onlyOnForms(),
            FormField::addTab('Listen')->onlyOnForms(),
            CollectionField::new('list')->onlyOnForms()
                ->setHelp("Titel erzeugt Teilüberschrift. Dazu nur erstes Feld ausfüllen.<br>Listen werden tabellarisch dargestellt.")
                ->setEntryIsComplex(true)
                ->useEntryCrudForm(ListEntryCrudController::class),

            FormField::addTab('Bildergalerie')->onlyOnForms(),
            AssociationField::new('gallery')->setFormTypeOptions([
                'expanded' => true
            ])->onlyOnForms(),
            FormField::addTab('Vertretungsplan')->onlyOnForms(),
            TextField::new('scheduleOffset')->setHelp('z.B. "+1 day" oder "+2 days"<br>(Funktion wird bei Bedarf implementiert.)')->onlyOnForms(),
        ];
    }

}
