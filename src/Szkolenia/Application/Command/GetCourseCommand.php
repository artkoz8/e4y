<?php

namespace App\Szkolenia\Application\Command;

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