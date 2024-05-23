<?php

namespace App\Courses\Application\Command;

use App\Courses\Entity\Course;

class SaveCourseCommand
{
    public function __construct(
        readonly private Course $training
    ) {}

    public function getTraining(): Course
    {
        return $this->training;
    }
}