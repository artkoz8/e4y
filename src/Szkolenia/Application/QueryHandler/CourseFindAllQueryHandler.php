<?php

namespace App\Szkolenia\Application\QueryHandler;

use App\Entity\Training;
use App\Repository\TrainingRepository;
use App\Szkolenia\Application\Query\CourseFindAllQuery;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CourseFindAllQueryHandler
{
    public function __construct(
        private TrainingRepository $trainingRepository
    ) {}

    /**
     * @return Training[]
     */
    public function __invoke(CourseFindAllQuery $query): array
    {
        return $this->trainingRepository->findAll();
    }
}