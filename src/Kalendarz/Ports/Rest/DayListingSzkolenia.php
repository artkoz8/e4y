<?php

namespace App\Kalendarz\Ports\Rest;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DayListingSzkolenia extends AbstractController
{
    #[Route('kalendarz/szkolenia', name: 'dayLlistingSzkolenia', methods: ['GET'])]
    public function __invoke(): Response
    {
        return new JsonResponse([]);
    }
}