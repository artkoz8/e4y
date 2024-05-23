<?php

namespace App\Courses\Application\Command;

use App\Entity\Training;

class SaveCourseCommand
{
    public function __construct(
        readonly private Training $training
    ) {}

    public function getTraining(): Training
    {
        return $this->training;
    }
}