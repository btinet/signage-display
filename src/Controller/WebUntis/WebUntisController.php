<?php

namespace App\Controller\WebUntis;

use App\UntisModel\SubstitutionsModel;
use App\UntisModel\WebUntis;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Webuntis\Configuration\WebuntisConfiguration;
use Webuntis\Configuration\YAMLConfiguration;
use Webuntis\Query\Query;

#[Route('/admin/webuntis', name: 'admin_untis_')]
class WebUntisController extends AbstractController
{



    #[Route('/index', name: 'index')]
    public function index(): Response
    {
        $untis = new WebUntis();
        $auth = $untis->auth("samos.webuntis.com","Wagner Pictures School of Photography","admin","Camilla@23");
        $subjects   = $untis->getSubjects();
        return $this->render('admin/untis/index.html.twig', [
            'query' => $auth,
            'result' => $subjects,
            'teachers' => null
        ]);
    }

}