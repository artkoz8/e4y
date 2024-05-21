<?php

namespace App\Szkolenia\Application\Factory;

use App\Common\ValueObject\Money;
use App\Entity\CourseLeader;
use App\Entity\Training;
use App\Szkolenia\Application\Exception\DateOfCourseExpiredException;
use DateTime;

class CourseFactory
{
    public static function create(
        CourseLeader $courseLeader,
        string       $name,
        float        $price,
        DateTime     $dateOfCourse
    ): Training
    {
        if ($dateOfCourse->getTimestamp() <= (new DateTime())->getTimestamp()) {
            throw new DateOfCourseExpiredException("Course start date is expire");
        }

        $course = new Training();
        $course->setName($name);
        $course->setPrice(Money::PLNFromFloat($price));
        $course->setDateOfTraining($dateOfCourse);
        $course->setCourseLeader($courseLeader);

        return $course;
    }
}