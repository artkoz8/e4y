<?php

namespace App\Courses\Application\CommandHandler;

use App\Courses\Application\Command\SaveCourseCommand;
use App\Courses\Repository\CourseRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SaveCourseCommandHandler
{
    public function __construct(
        readonly private CourseRepository $trainingRepository
    ) {}

    public function __invoke(SaveCourseCommand $command): void
    {
        $this->trainingRepository->save($command->getTraining());
    }
}