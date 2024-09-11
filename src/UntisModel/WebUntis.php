<?php

namespace App\UntisModel;

use App\Entity\WebUntisServer;
use App\Repository\WebUntisServerRepository;
use PHPUnit\Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebUntis
{

    private string $id;
    private string $sessionId;

    private ?int $personType;
    private ?int $personId;
    private HttpClientInterface $httpClient;
    private string $serverDomain = 'webuntis.com';
    private string $serverScript = '/WebUntis/jsonrpc.do';
    private ?WebUntisServer $serverObject;
    private array $header;
    private string $apiVersion = '2.0';
    private ?string $body;
    private string $method;
    private array $params;

    public const KLASSE = 1;
    public const TEACHER = 2;
    public const SUBJECT = 3;
    public const ROOM = 4;
    public const STUDENT = 5;

    public function __construct(HttpClientInterface $client, WebUntisServerRepository $serverRepository)
    {
        // create ID
        $this->id = md5(time().rand());

        $this->httpClient = $client;
        $this->serverObject = $serverRepository->findOneBy(['active' => true]);
        $this->resetQuery();
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function auth(): bool
    {
        try {
            $this
                ->setMethod('authenticate')
                ->addParam('user', $this->serverObject->getUsername())
                ->addParam('password', $this->serverObject->getPassword())
                ->addParam('client', 'web')
                ->buildQuery();

            $response = $this->execute();

            if (is_array($response) && array_key_exists('result', $response)) {
                $this->sessionId = $response['result']['sessionId'];
                $this->personType = $response['result']['personType'];
                $this->personId = $response['result']['personId'];
                return true;
            }
        } catch (\Error|ClientExceptionInterface $e) {

        }
        return false;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function logout(): bool
    {
        if($this->sessionId) {
            $this
                ->setMethod('logout')
                ->setSessionCookie()
                ->buildQuery()
            ;

            if($this->execute()) return true;
        }
        return false;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getSubstitutions(string $startDate, string $endDate, string $departement = "0"): ?array
    {
        if($this->sessionId) {
            $this
                ->setMethod('getSubstitutions')
                ->setSessionCookie()
                ->addParam('startDate',$startDate)
                ->addParam('endDate',$endDate)
                ->addParam('departmentId',$departement)
                ->buildQuery()
            ;
            if($response = $this->execute()) return $response;
        }
        return null;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getStatusData(){
        if($this->sessionId) {
            $this
                ->setMethod('getStatusData')
                ->setSessionCookie()
                ->buildQuery()
            ;
            if($response = $this->execute()) return $response['result'] ?? [];
        }
        return null;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getTimeGrid(): ?array
    {
        if($this->sessionId) {
            $this
                ->setMethod('getTimegridUnits')
                ->setSessionCookie()
                ->buildQuery()
            ;
            if($response = $this->execute()) return $response['result'] ?? [];
        }
        return null;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getSubjects(){
        if($this->sessionId) {
            $this
                ->setMethod('getSubjects')
                ->setSessionCookie()
                ->buildQuery()
            ;
            if($response = $this->execute()) return $response['result'] ?? [];
        }
        return null;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getTeachers(){
        if($this->sessionId) {
            $this
                ->setMethod('getTeachers')
                ->setSessionCookie()
                ->buildQuery()
            ;
            if($response = $this->execute()) return $response['result'] ?? [];
        }
        return null;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getKlassen(){
        if($this->sessionId) {
            $this
                ->setMethod('getKlassen')
                ->setSessionCookie()
                ->buildQuery()
            ;
            if($response = $this->execute()) return $response['result'] ?? [];
        }
        return null;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getRooms(){
        if($this->sessionId) {
            $this
                ->setMethod('getRooms')
                ->setSessionCookie()
                ->buildQuery()
            ;
            if($response = $this->execute()) return $response['result'] ?? [];
        }
        return null;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getHolidays(){
        if($this->sessionId) {
            $this
                ->setMethod('getHolidays')
                ->setSessionCookie()
                ->buildQuery()
            ;
            if($response = $this->execute()) return $response['result'] ?? [];
        }
        return null;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getCurrentSchoolYear(){
        if($this->sessionId) {
            $this
                ->setMethod('getCurrentSchoolyear')
                ->setSessionCookie()
                ->buildQuery()
            ;
            if($response = $this->execute()) return $response['result'] ?? [];
        }
        return null;
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function getTimetable($id, $type){
        if($this->sessionId) {
            $this
                ->setMethod('getTimetable')
                ->addParam('id',$id)
                ->addParam('type',$type)
                ->setSessionCookie()
                ->buildQuery()
            ;
            if($response = $this->execute()) return $response['result'] ?? [];
        }
        return null;
    }


    // Utility-Methods
    private function setMethod(string $method): self
    {
        $this->method = $method;
        return $this;
    }

    private function addHeader(array $header): void
    {
        $this->header = array_merge($this->header, $header);
    }

    private function setSessionCookie(): self
    {
        $this->addHeader(['Cookie' => 'JSESSIONID='.$this->sessionId]);
        return $this;
    }

    private function addParam(string $param, $value): self
    {
        $this->params[$param] = $value;
        return $this;
    }

    private function buildQuery(): void
    {
        $body['id'] = $this->id;
        $body['method'] = $this->method;
        $body['params'] = $this->params;
        $body['jsonrpc'] = $this->apiVersion;
        $this->body = json_encode($body);
    }

    private function resetQuery(): void
    {
        $this->params = [];
        $this->header = ['Content-Type' => 'application/json'];
        $this->body = null;
    }

    /**
     * @throws TransportExceptionInterface
     */
    private function execute(): ?array
    {
        $url = sprintf("https://%s.%s%s?school=%s",$this->serverObject->getServer(),$this->serverDomain,$this->serverScript,$this->serverObject->getSchoolName());

        $response = $this->httpClient->request('POST', $url, [
            'headers' => $this->header,
            'body' => $this->body,
        ]);
        try {
            $this->resetQuery();
            return $response->toArray();
        } catch (TransportExceptionInterface|ClientExceptionInterface|DecodingExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface $e) {

        }
        return null;
    }

    // Getters & Setters

    public function getPersonType(): ?int
    {
        return $this->personType;
    }

    public function getPersonId(): ?int
    {
        return $this->personId;
    }

}