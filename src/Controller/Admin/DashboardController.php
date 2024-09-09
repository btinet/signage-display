<?php

namespace App\Controller\Admin;

use App\Entity\BlogPost;
use App\Entity\BlogPostTemplate;
use App\Entity\ClassGroup;
use App\Entity\Course;
use App\Entity\CourseEntry;
use App\Entity\CourseEntryFilter;
use App\Entity\CourseEvent;
use App\Entity\Image;
use App\Entity\ImageGallery;
use App\Entity\ListEntry;
use App\Entity\MessageType;
use App\Entity\ScheduleType;
use App\Entity\SchoolSubject;
use App\Entity\ShoutOut;
use App\Entity\SuspensionEntry;
use App\Entity\Teacher;
use App\Entity\UntisImport;
use App\Entity\User;
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
        yield MenuItem::linkToCrud('GPU Upload','fa fa-file',UntisImport::class)->setAction('new');
        yield MenuItem::linkToRoute('WebUntis',"fa fa-clock",'admin_untis_index');
        yield MenuItem::linkToCrud('Course Entries','fa fa-list',CourseEntry::class);

        yield MenuItem::section('Aushang');
        yield MenuItem::linkToCrud('BlogPosts','fa fa-pen-nib',BlogPost::class);
        yield MenuItem::linkToCrud('ShoutOuts','fa fa-bullhorn',ShoutOut::class);
        yield MenuItem::linkToCrud('Galleries','fa fa-images',ImageGallery::class);
        yield MenuItem::linkToCrud('Images','fa fa-image',Image::class);

        yield MenuItem::section('Setup');
        yield MenuItem::linkToCrud('Course Keywords','fa fa-list',CourseEntryFilter::class);
        yield MenuItem::linkToCrud('Types All','fa fa-fire',ScheduleType::class);
        yield MenuItem::linkToCrud('MessageTypes','fa fa-bullhorn',MessageType::class);
        yield MenuItem::linkToCrud('BlogPostTemplates','fa fa-swatchbook',BlogPostTemplate::class);
        yield MenuItem::linkToCrud('Users','fa fa-users',User::class);


        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
