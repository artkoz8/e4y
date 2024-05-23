<?php

namespace App\Courses\Application\Query;

use App\CourseLeader\Entity\CourseLeader;
use DateTimeInterface;

class CourseFindByQuery
{
    public function __construct(
        private ?CourseLeader      $courseLeader = null,
        private ?string            $courseName = null,
        private ?DateTimeInterface $startDate = null,
        private ?DateTimeInterface $endDate = null,
    )
    {
    }

    public function getCourseLeader(): ?CourseLeader
    {
        return $this->courseLeader;
    }

    public function getCourseName(): ?string
    {
        return $this->courseName;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }
}