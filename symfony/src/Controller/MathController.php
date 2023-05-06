<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MathController extends AbstractController
{
  #[Route('/math', name: 'app_math')]
  public function index(Request $request): JsonResponse
  {
    $number = $request->query->get('number') ?? null;

    if($number != null){
      if(is_numeric($number)) return $this->json((int) $number + 1);
      if(!is_numeric($number)) return $this->json('Invalid query parameter number: Not a number', 400);
    }

    $numberList = $request->query->get('numbers') ?? null;

    if($numberList != null){
      if(json_decode($numberList) != null){
        $numberList = json_decode($numberList);

        $result = $numberList[0];
        for ($i = 1; $i < sizeof($numberList); $i++){

          if(!is_numeric($numberList[$i])) return $this->json('Invalid query parameter numbers: Not a list of numbers', 400);

          $result = ((($numberList[$i] * $result)) /
                  (gmp_intval(gmp_gcd($numberList[$i], $result))));
        }

        return $this->json($result);
      } else {
        return $this->json('Invalid query parameter numbers: Not a list', 400);
      }
    }

    return $this->json('No query parameter passed', 400);
  }
}
