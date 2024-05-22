<?php

namespace App\Szkolenia\Application\Command;

use DateTime;

class UpdateCourseCommand
{
    public function __construct(
        private int $courseId,
        private ?string $name = null,
        private ?string $courseLeaderName = null,
        private ?string $courseLeaderSurname = null,
        private ?float $price = null,
        private ?DateTime $dateOfCourse = null,
    )
    {}

    public function getCourseId(): int
    {
        return $this->courseId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getCourseLeaderName(): ?string
    {
        return $this->courseLeaderName;
    }

    public function getCourseLeaderSurname(): ?string
    {
        return $this->courseLeaderSurname;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function getDateOfCourse(): ?DateTime
    {
        return $this->dateOfCourse;
    }
}