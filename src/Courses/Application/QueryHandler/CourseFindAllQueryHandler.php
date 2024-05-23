<?php

namespace App\Courses\Application\QueryHandler;

use App\Entity\Training;
use App\Repository\TrainingRepository;
use App\Courses\Application\Query\CourseFindByQuery;
use Doctrine\ORM\Query\Expr\Comparison;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CourseFindAllQueryHandler
{
    public function __construct(
        private TrainingRepository $trainingRepository
    )
    {
    }

    /**
     * @return Training[]
     */
    public function __invoke(CourseFindByQuery $query): array
    {
        $qb = $this->trainingRepository->createQueryBuilder('course');
        $qb->where('1=1');

        if ($query->getCourseLeader() !== null) {
            $qb->andWhere($qb->expr()->eq('course.courseLeader', ':courseLeader'))
                ->setParameter('courseLeader', $query->getCourseLeader());
        }

        if ($query->getCourseName() !== null) {
            $qb->andWhere($qb->expr()->eq('course.name', ':courseName'))
                ->setParameter('courseName', $query->getCourseName());
        }

        if ($query->getStartDate() !== null) {
            $expr = $qb->expr()->gte('course.dateOfTraining', ':startDate');
            $qb->andWhere($expr)->setParameter('startDate', $query->getStartDate());
        }

        if ($query->getEndDate() !== null) {
            $expr = $qb->expr()->lte('course.dateOfTraining', ':endDate');
            $qb->andWhere($expr)->setParameter('endDate', $query->getEndDate());
        }

        return $qb->getQuery()->getResult();
    }
}