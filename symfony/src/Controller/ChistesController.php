<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ChistesController extends AbstractController
{

    private $apis;
    private $apiHeaders;

    public function __construct(private HttpClientInterface $client){
        $this->apis = [
            'Chuck' => 'https://api.chucknorris.io/jokes/random',
            'Dad' => 'https://icanhazdadjoke.com/',
        ];
        $this->apiHeaders = [
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => 'test-php-PabloMuro'
            ],
        ];
    }

    #[Route('/chistes/{apiName}', name: 'app_chistes')]
    public function index(string $apiName = null): JsonResponse
    {

        $randName = array_rand($this->apis,1);
        $apiName = $apiName ? $apiName : $randName;

        $apiUrl = $this->apis[$apiName]  ?? null;

        if($apiUrl != null){
            
            $response = $this->client->request(
                'GET',
                $apiUrl,
                $this->apiHeaders 
            );

            $responseArray = $response->toArray();
            $joke = $responseArray['value'] ?? $responseArray['joke'];
            return $this->json([
                'status' => $response->getStatusCode(),
                'chiste' => $joke,
            ]);
        }
        
        return $this->json([
            'error' => 'No chiste found',
        ]);
    }
}
