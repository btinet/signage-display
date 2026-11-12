<?php

namespace App\Controller\Admin;

use App\Entity\DisplayMacro;
use App\Repository\BlogPostRepository;
use App\Repository\ShoutOutRepository;
use App\Service\InfoScreenSettings;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class DisplayMacroCrudController extends AbstractCrudController
{

    public function __construct(
        private BlogPostRepository $blogPostRepository,
        private ShoutOutRepository $shoutOutRepository,
        private InfoScreenSettings $infoScreenSettings,
    )
    {
    }

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

    public function configureActions(Actions $actions): Actions
    {
        $actions->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
            return $action->displayIf(static function ($entity) {
                return $entity->getRunningDisplayMacros()->isEmpty();
            });
        });
        $actions->update(Crud::PAGE_DETAIL, Action::EDIT, function (Action $action) {
            return $action->displayIf(static function ($entity) {
                return $entity->getRunningDisplayMacros()->isEmpty();
            });
        });
        $actions->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
            return $action->displayIf(static function ($entity) {
                return $entity->getRunningDisplayMacros()->isEmpty();
            });
        });
        $actions->update(Crud::PAGE_DETAIL, Action::DELETE, function (Action $action) {
            return $action->displayIf(static function ($entity) {
                return $entity->getRunningDisplayMacros()->isEmpty();
            });
        });
        return $actions;
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof DisplayMacro) {
            return;
        }
        $activePosts = $this->blogPostRepository->findCurrentBlogPosts();
        $activeShoutOuts = $this->shoutOutRepository->findCurrentEntries();
        $showScheduleColumn = $this->infoScreenSettings->get('show_schedule_column');

        foreach ($activePosts as $post) {
            $entityInstance->addPrevBlogPost($post);
        }
        foreach ($activeShoutOuts as $shoutOut) {
            $entityInstance->addPrevShoutOut($shoutOut);
        }
        $entityInstance->setPrevShowScheduleColumn($showScheduleColumn);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof DisplayMacro) {
            return;
        }
        $activePosts = $this->blogPostRepository->findCurrentBlogPosts();
        $activeShoutOuts = $this->shoutOutRepository->findCurrentEntries();
        $showScheduleColumn = $this->infoScreenSettings->get('show_schedule_column');

        foreach ($entityInstance->getPrevBlogPosts() as $post) {
            $entityInstance->removePrevBlogPost($post);
        }
        foreach ($entityInstance->getPrevShoutOuts() as $shoutOut) {
            $entityInstance->removePrevShoutOut($shoutOut);
        }
        foreach ($activePosts as $post) {
            $entityInstance->addPrevBlogPost($post);
        }
        foreach ($activeShoutOuts as $shoutOut) {
            $entityInstance->addPrevShoutOut($shoutOut);
        }
        $entityInstance->setPrevShowScheduleColumn($showScheduleColumn);
        parent::updateEntity($entityManager, $entityInstance);
    }

}
