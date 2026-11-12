<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\BlogPostTemplate;
use App\Entity\ClassGroup;
use App\Entity\Course;
use App\Entity\CourseEntry;
use App\Entity\CourseEntryFilter;
use App\Entity\CourseEvent;
use App\Entity\DisplayMacro;
use App\Entity\Image;
use App\Entity\ImageGallery;
use App\Entity\ListEntry;
use App\Entity\MessageType;
use App\Entity\ScheduleGrid;
use App\Entity\ScheduleType;
use App\Entity\SchoolSubject;
use App\Entity\ShoutOut;
use App\Entity\SuspensionEntry;
use App\Entity\Teacher;
use App\Entity\UntisImport;
use App\Entity\User;
use App\Entity\WebUntisServer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        //return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<div style="display: flex; align-items: center"><img alt="Logo" src="/favicon-32x32.png" style="margin-right: .5rem"> Signage Display</div>')
            ->setFaviconPath('images/favicon.ico')
            ->renderContentMaximized()
            ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Display zeigen','fa fa-display',$this->generateUrl('app_index'));

        yield MenuItem::section('Stundenplan');
        yield MenuItem::linkTo(UntisImportCrudController::class, 'GPU Upload', 'fa fa-file')->setAction('new');
        yield MenuItem::linkToRoute('WebUntis', 'fa fa-clock', 'admin_untis_index')->setBadge("dev", "danger");
        yield MenuItem::linkTo(CourseEntryCrudController::class, 'Course Entries', 'fa fa-list');

        yield MenuItem::section('Aushang');
        yield MenuItem::linkTo(BlogPostCrudController::class, 'BlogPosts', 'fa fa-pen-nib');
        yield MenuItem::linkTo(ShoutOutCrudController::class, 'ShoutOuts', 'fa fa-bullhorn');
        yield MenuItem::linkTo(ImageGalleryCrudController::class, 'Galleries', 'fa fa-images');
        yield MenuItem::linkTo(ImageGalleryCrudController::class, 'Images', 'fa fa-image');

        yield MenuItem::section('Automatisieren')->setBadge("neu", "info");;
        yield MenuItem::linkTo(DisplayMacroCrudController::class, 'DisplayMakro', 'fa fa-robot'); // bereits neu
        yield MenuItem::linkTo(RunningDisplayMacroCrudController::class, 'Running DisplayMakro', 'fa fa-list'); // bereits neu

        yield MenuItem::section('Setup');
        yield MenuItem::linkToRoute('Infoscreen-Einstellungen', 'fa fa-tv', 'admin_infoscreen_settings');
        yield MenuItem::linkTo(CourseEntryFilterCrudController::class, 'Course Keywords', 'fa fa-list');
        yield MenuItem::linkTo(ScheduleTypeCrudController::class, 'Types All', 'fa fa-fire');
        yield MenuItem::linkTo(MessageTypeCrudController::class, 'MessageTypes', 'fa fa-bullhorn');
        yield MenuItem::linkTo(BlogPostTemplateCrudController::class, 'BlogPostTemplates', 'fa fa-swatchbook');
        yield MenuItem::linkTo(UserCrudController::class, 'Users', 'fa fa-users');
        yield MenuItem::linkTo(ScheduleGridCrudController::class, 'Schedule Grid', 'fa fa-table-cells-large');
        yield MenuItem::linkTo(WebUntisServerCrudController::class, 'UntisUsers', 'fa fa-users');


        // yield MenuItem::linkTo('The Label', 'fas fa-list', EntityClass::class);
    }
}
