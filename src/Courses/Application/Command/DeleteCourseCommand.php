<?php

namespace App\Courses\Application\Command;

class DeleteCourseCommand
{
    public function __construct(
        private int  $courseId
    )
    {
    }

    public function getCourseId(): int
    {
        return $this->courseId;
    }
}