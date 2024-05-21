<?php

namespace App\CourseLeader\Application\Query;

class FindCourseLeaderQuery
{
    public function __construct(
        private string $name,
        private string $surname,
    )
    {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }
}