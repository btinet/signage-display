<?php

namespace App\Controller;

use App\Repository\CourseEntryRepository;
use App\Repository\ImageGalleryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'app_')]
class AppController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ImageGalleryRepository $imageGalleryRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $galleries = $imageGalleryRepository->findActiveEntries();
        return $this->render('app/index.html.twig', [
            'galleries' => $galleries
        ]);
    }

    #[Route('/privacy', name: 'privacy')]
    public function privacyPolicy(): Response
    {
        return $this->render('app/privacy.html.twig');
    }

    #[Route('/imprint', name: 'imprint')]
    public function imprint(): Response
    {
        return $this->render('app/imprint.html.twig');
    }

}
