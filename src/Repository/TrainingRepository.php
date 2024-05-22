<?php

namespace App\Repository;

use App\Entity\Training;
use App\Szkolenia\Application\Exception\CourseSaveException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;

/**
 * @extends ServiceEntityRepository<Training>
 */
class TrainingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Training::class);
    }

    public function save(Training $course)
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

    public function delete(Training $course): void
    {
        $this->getEntityManager()->remove($course);
        $this->getEntityManager()->flush();
    }
}
