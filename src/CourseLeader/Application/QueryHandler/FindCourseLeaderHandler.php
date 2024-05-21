<?php

namespace App\CourseLeader\Application\QueryHandler;

use App\CourseLeader\Application\Exception\FoundMultipleCourseLeaderException;
use App\CourseLeader\Application\Exception\NotFoundCourseLeaderException;
use App\CourseLeader\Application\Query\FindCourseLeaderQuery;
use App\Entity\CourseLeader;
use App\Repository\CourseLeaderRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class FindCourseLeaderHandler
{
    public function __construct(
        private CourseLeaderRepository $courseLeaderRepository
    )
    {}

    public function __invoke(FindCourseLeaderQuery $query): CourseLeader
    {
        $courseLeader = $this->courseLeaderRepository->findBy([
            'name' => $query->getName(),
            'surname' => $query->getSurname()
        ]);

        if (count($courseLeader) === 0) {
            throw new NotFoundCourseLeaderException("CourseLeader \"{$query->getName()} {$query->getSurname()}\" not found.");
        }

        if (count($courseLeader) > 1) {
            throw new FoundMultipleCourseLeaderException("Find more than one course leader");
        }

        return $courseLeader[0];
    }
}