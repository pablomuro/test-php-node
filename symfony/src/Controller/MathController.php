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
        $numberList = $request->query->get('numbers') ?? null;
        $number = $request->query->get('number') ?? null;

        if($number){
            return $this->json((int) $number + 1);
        }

        if($numberList){
            var_dump(json_decode($numberList));
            return $this->json('numberList test');
        }

        return $this->json('Nothing to do');
    }
}
