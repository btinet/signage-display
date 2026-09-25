<?php

namespace App\Controller\Admin;

use App\Form\SettingsType;
use App\Service\InfoScreenSettings;
use EasyCorp\Bundle\EasyAdminBundle\Provider\AdminContextProvider; // <--- WICHTIG
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SettingsAdminController extends AbstractController
{
    #[Route('/admin/infoscreen-settings', name: 'admin_infoscreen_settings')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(
        Request $request,
        InfoScreenSettings $settings,
        AdminContextProvider $adminContextProvider // <--- INJEKTION
    ): Response {

        // 1. Aktuelle Daten aus dem Service holen
        $formData = [
            'reload_interval' => $settings->get('reload_interval', 1800),
            'show_schedule_column' => $settings->get('show_schedule_column', true),
        ];

        // 2. Formular erstellen
        $form = $this->createForm(SettingsType::class, $formData);
        $form->handleRequest($request);

        // 3. Speichern, wenn abgesendet
        if ($form->isSubmitted() && $form->isValid()) {
            $settings->save($form->getData());
            $this->addFlash('success', 'Die Einstellungen wurden erfolgreich gespeichert.');

            return $this->redirectToRoute('admin');
        }

        // 4. Template rendern MIT dem EasyAdmin-Kontext
        return $this->render('admin/settings.html.twig', [
            'form' => $form->createView(),
            'ea' => $adminContextProvider->getContext(), // <--- HIER ÜBERGEBEN
        ]);
    }
}