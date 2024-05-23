<?php

namespace App\Courses\Application\Command;

class GetCourseCommand
{
    public function __construct(
        private int $courseId
    ) {}

    public function getCourseId(): int
    {
        return $this->courseId;
    }
}