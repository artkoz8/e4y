<?php

namespace App\Courses\Repository;

use App\Courses\Application\Exception\CourseSaveException;
use App\Courses\Entity\Course;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * @extends ServiceEntityRepository<Course>
 */
class CourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    public function save(Course $course)
    {
        try {
            $this->getEntityManager()->persist($course);
            $this->getEntityManager()->flush();
        } catch (UniqueConstraintViolationException $exception) {
            throw new CourseSaveException("Course \"{$course->getName()}\" already exists", 500, $exception);
        } catch (Throwable $exception) {
            throw new CourseSaveException("Error save course \"{$course->getName()}\". {$exception->getMessage()}", 500, $exception);
        }
    }

    public function delete(Course $course): void
    {
        $this->getEntityManager()->remove($course);
        $this->getEntityManager()->flush();
    }
}
