<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\JokeRepository;
use App\Entity\Joke;


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

  public function __construct(private HttpClientInterface $client, private JokeRepository $jokeRepository ){
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
    $randIndex = array_rand($this->apis,1);
    $apiName = $apiName ? $apiName : $randIndex;

    $apiUrl = $this->apis[$apiName]  ?? null;

    if($apiUrl != null){
      $response = $this->client->request(
        'GET',
        $apiUrl,
        $this->apiHeaders 
      );

      $responseArray = $response->toArray();
      $joke = $responseArray['value'] ?? $responseArray['joke'];
      return $this->json($joke);
    }
    
    return $this->json('No Joke Found');
  }

  #[Route('/joke', name: 'app_joke_new', methods: ['POST'])]
  public function create(Request $request): JsonResponse
  {
    $jokeText = $request->request->get('joke');

    if(!$jokeText) return $this->json('Not Joke Text');

    $newJoke = new Joke();
    $newJoke->setJokeText($jokeText);
    $this->jokeRepository->create($newJoke);
    var_dump($newJoke->getId());
    return $this->json($newJoke->toJson());
  }

  #[Route('/joke', name: 'app_joke_update', methods: ['PUT'])]
  public function update(Request $request): JsonResponse
  {
    $id = $request->request->get('number');

    if(!$id) return $this->json('Not Found');

    $jokeText = $request->request->get('joke');

    if(!$jokeText) return $this->json('Not Joke Text');

    $joke = $this->jokeRepository->find($id);
    if($joke){
      $joke->setJokeText($jokeText);
      $this->jokeRepository->update($joke);
      return $this->json($joke->toJson());
    }

    return $this->json('Not Found');
  }

  #[Route('/joke', name: 'app_joke_delete', methods: ['DELETE'])]
  public function delete(int $id): JsonResponse
  {
    $id = $request->request->get('number');

    if(!$id) return $this->json('Not Found');

    $joke = $this->jokeRepository->find($id);
    if($joke){
      $this->jokeRepository->delete($joke);
      return $this->json('Joke Removed');
    }

    return $this->json('Not Found');
  }
}
