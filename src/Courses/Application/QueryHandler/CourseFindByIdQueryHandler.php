<?php

namespace App\Courses\Application\QueryHandler;

use App\Entity\Training;
use App\Repository\TrainingRepository;
use App\Courses\Application\Exception\CourseNotFoundException;
use App\Courses\Application\Query\CourseFindByIdQuery;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CourseFindByIdQueryHandler
{
    public function __construct(
        private TrainingRepository $trainingRepository,
    ) {}

    public function __invoke(CourseFindByIdQuery $query): Training
    {
        $course = $this->trainingRepository->find($query->getCourseId());
        if ($course === null) {
            throw new CourseNotFoundException("Course with id {$query->getCourseId()} not found.");
        }

        return $course;
    }
}