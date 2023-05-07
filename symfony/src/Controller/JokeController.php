<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Repository\JokeRepository;
use App\Entity\Joke;

class JokeController extends AbstractController
{

  private $apis;
  private $apiHeaders;

  public function __construct(
    private HttpClientInterface $client,
    private JokeRepository $jokeRepository,
    private ValidatorInterface $validator,
    private HtmlSanitizerInterface $htmlSanitizer
  ){
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
    $apiName = $apiName ? $this->htmlSanitizer->sanitize($apiName) : $randIndex;

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
    
    return $this->json('No Joke Found', 400);
  }

  #[Route('/joke', name: 'app_joke_new', methods: ['POST'])]
  public function create(Request $request): JsonResponse
  {
    $jokeText = $this->htmlSanitizer->sanitize($request->request->get('joke'));

    $violations = $this->validator->validate($jokeText, new Assert\NotBlank);

    if (count($violations) > 0) {
      // TODO - validate request params based on validation rules
    }

    if(!$jokeText) return $this->json('Invalid Joke Text', 400);

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
    $jokeText = $request->request->get('joke');

    if(!$id) return $this->json('Not Found', 400);
    if(!$jokeText) return $this->json('Invalid Joke Text', 400);

    $id = $this->htmlSanitizer->sanitize($id);
    $jokeText = $this->htmlSanitizer->sanitize($jokeText);

    $joke = $this->jokeRepository->find($id);
    if($joke){
      $joke->setJokeText($jokeText);
      $this->jokeRepository->update($joke);
      return $this->json($joke->toJson());
    }

    return $this->json('Not Found', 400);
  }

  #[Route('/joke', name: 'app_joke_delete', methods: ['DELETE'])]
  public function delete(Request $request): JsonResponse
  {
    $id = $this->htmlSanitizer->sanitize($request->request->get('number'));

    if(!$id) return $this->json('Not Found', 400);

    $joke = $this->jokeRepository->find($id);
    if($joke){
      $this->jokeRepository->delete($joke);
      return $this->json('Joke Removed');
    }

    return $this->json('Not Found', 400);
  }
}
