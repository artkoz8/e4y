<?php

namespace App\Courses\Application\QueryHandler;

use App\Courses\Application\Exception\CourseNotFoundException;
use App\Courses\Application\Query\CourseFindByIdQuery;
use App\Courses\Entity\Course;
use App\Courses\Repository\CourseRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CourseFindByIdQueryHandler
{
    public function __construct(
        private CourseRepository $trainingRepository,
    ) {}

    public function __invoke(CourseFindByIdQuery $query): Course
    {
        $course = $this->trainingRepository->find($query->getCourseId());
        if ($course === null) {
            throw new CourseNotFoundException("Course with id {$query->getCourseId()} not found.");
        }

        return $course;
    }
}