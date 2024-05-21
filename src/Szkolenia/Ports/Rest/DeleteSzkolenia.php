<?php

namespace App\Szkolenia\Ports\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeleteSzkolenia extends AbstractController
{
    #[Route('/szkolenia/{szkolenieId}', name: 'deleteSzkolenia', methods: ['DELETE'], requirements: ['szkolenieId' => '\d+'])]
    public function __invoke(int $szkolenieId): Response
    {
        return new JsonResponse([]);
    }
}