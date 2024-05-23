<?php

namespace App\Courses\Application\Query;

class CourseFindByIdQuery
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