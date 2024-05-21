<?php

namespace App\Szkolenia\Application\CommandHandler;

use App\Entity\Training;
use App\Repository\TrainingRepository;
use App\Szkolenia\Application\Command\SaveCourseCommand;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SaveCourseCommandHandler
{
    public function __construct(
        readonly private TrainingRepository $trainingRepository
    ) {}

    public function __invoke(SaveCourseCommand $command): void
    {
        $this->trainingRepository->save($command->getTraining());
    }
}