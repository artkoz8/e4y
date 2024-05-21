<?php

namespace App\Szkolenia\Application\Command;

use DateTime;

class CreateCourseCommand
{
    public function __construct(
        private string   $name,
        private DateTime $dateOfCourse,
        private float    $price,
        private string   $courseLeaderName,
        private string   $courseLeaderSurname,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getDateOfCourse(): DateTime
    {
        return $this->dateOfCourse;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCourseLeaderName(): string
    {
        return $this->courseLeaderName;
    }

    public function getCourseLeaderSurname(): string
    {
        return $this->courseLeaderSurname;
    }
}