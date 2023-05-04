<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\ChisteRepository;
use App\Entity\Chiste;

class ChistesController extends AbstractController
{

  private $apis;
  private $apiHeaders;

  public function __construct(private HttpClientInterface $client, private ChisteRepository $chisteRepository ){
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

  #[Route('/chistes/{apiName}', name: 'app_chistes_get', methods: ['GET'])]
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

  #[Route('/chiste', name: 'app_chistes_new', methods: ['POST'])]
  public function create(Request $request): JsonResponse
  {
    $newJoke = new Chiste();
    $newJoke->setJoke('new joke');
    $this->chisteRepository->create($newJoke);
    return $this->json('Joke created');
  }

  #[Route('/chiste/{id}', name: 'app_chistes_update', methods: ['PUT'])]
  public function update(int $id): JsonResponse
  {
    $joke = $this->chisteRepository->find($id);
    if($joke){
      $joke->setJoke('updated joke $id');
      $this->chisteRepository->update($joke);
      return $this->json('Updated');
    }

    return $this->json('Not Found');
  }

  #[Route('/chiste/{id}', name: 'app_chistes_delete', methods: ['DELETE'])]
  public function delete(int $id): JsonResponse
  {
    $joke = $this->chisteRepository->find($id);
    if($joke){
      $this->chisteRepository->delete($joke);
      return $this->json('Deleted');
    }

    return $this->json('Not Found');
  }
}
