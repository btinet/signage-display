<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'api_')]
class ApiController extends AbstractController
{

    #[Route('/departures/{stationId}/{query}', name: 'departures')]
    public function getDepartures(string $stationId, string $query): Response
    {
        return $this->render('api/departures.html.twig', [
            'result' => $this->getTimeTable($stationId, $query)
        ]);
    }

    #[Route('/stops/{query}', name: 'stops')]
    public function getStopName(string $query): Response
    {
        return $this->render('api/stops.html.twig', [
            'result' => $this->getStops($query)
        ]);
    }

    private function getTimeTable(string $stationId, string $query = null) {

        if($query) {
            $query = "&$query";
        }
        // $ch = curl_init("https://v6.bvg.transport.rest/locations?poi=false&addresses=false&query=$stop$query");
        $ch = curl_init("https://v6.bvg.transport.rest/stops/$stationId/departures?duration20&results=2$query");

        curl_setopt_array($ch, array(
            CURLOPT_POST => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE
        ));

        $response = curl_exec($ch);

        if ($response === FALSE) {
            $responseData['message'] = curl_error($ch);
        } else {
            $responseData = json_decode($response, TRUE);
        }
        curl_close($ch);
        return $responseData;
    }

    private function getStops(string $query) {

        // $ch = curl_init("https://v6.bvg.transport.rest/locations?poi=false&addresses=false&query=$stop");
        $ch = curl_init("https://v6.bvg.transport.rest/stops?query=$query");

        curl_setopt_array($ch, array(
            CURLOPT_POST => FALSE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE
        ));

        $response = curl_exec($ch);

        if ($response === FALSE) {
            $responseData['message'] = curl_error($ch);
        } else {
            $responseData = json_decode($response, TRUE);
        }
        curl_close($ch);
        return $responseData;
    }

}