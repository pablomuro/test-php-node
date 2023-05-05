<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\jokeRepository;
use App\Entity\joke;


/**
 * Pegar dados do corpo, sanetizar dados
 * Validar dados do path e ID, string e numero via validator do Symfony
 * yaml para OpenApi: fazer no node
 * Unit test
 */
class JokeController extends AbstractController
{

  private $apis;
  private $apiHeaders;

  public function __construct(private HttpClientInterface $client, private jokeRepository $jokeRepository ){
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

  #[Route('/joke/{apiName}', name: 'app_joke_get', methods: ['GET'])]
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
        'joke' => $joke,
      ]);
    }
    
    return $this->json([
      'error' => 'No Joke Found',
    ]);
  }

  #[Route('/joke', name: 'app_joke_new', methods: ['POST'])]
  public function create(Request $request): JsonResponse
  {
    $jokeText = $request->request->get('joke');

    if(!$jokeText) return $this->json('Not Joke Text');

    $newJoke = new joke();
    $newJoke->setJoke($jokeText);
    $this->jokeRepository->create($newJoke);
    return $this->json('Joke created');
  }

  #[Route('/joke/{id}', name: 'app_joke_update', methods: ['PUT'])]
  public function update(Request $request): JsonResponse
  {
    $id = $request->request->get('number');

    if(!$id) return $this->json('Not Found');

    $jokeText = $request->request->get('joke');

    if(!$jokeText) return $this->json('Not Joke Text');

    $joke = $this->jokeRepository->find($id);
    if($joke){
      $joke->setJoke($jokeText);
      $this->jokeRepository->update($joke);
      return $this->json('Updated');
    }

    return $this->json('Not Found');
  }

  #[Route('/joke/{id}', name: 'app_joke_delete', methods: ['DELETE'])]
  public function delete(int $id): JsonResponse
  {
    $id = $request->request->get('number');

    if(!$id) return $this->json('Not Found');

    $joke = $this->jokeRepository->find($id);
    if($joke){
      $this->jokeRepository->delete($joke);
      return $this->json('Deleted');
    }

    return $this->json('Not Found');
  }
}
