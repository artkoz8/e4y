<?php

namespace App\Szkolenia\Ports\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddSzkolenia extends AbstractController
{
    #[Route('/szkolenia', name: 'addSzkolenia', methods: ['POST'])]
    public function __invoke(): Response
    {
        return new JsonResponse([]);
    }
}