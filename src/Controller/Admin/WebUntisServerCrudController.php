<?php

namespace App\Controller\Admin;

use App\Entity\WebUntisServer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class WebUntisServerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WebUntisServer::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('active'),
            TextField::new('title'),
            TextField::new('schoolName')->setRequired(true)->setHelp("Wie in WebUntis angegeben"),
            TextField::new('server')->setRequired(true)->setHelp("samos, thalia, kos, erato, ..."),
            TextField::new('username')->setRequired(true)->setHelp("Ihr WebUntis-Login"),
            TextField::new('password')->setRequired(true)->setHelp("Ihr WebUntis-Passwort")->setFormType(PasswordType::class)->onlyOnForms(),
        ];
    }

}
