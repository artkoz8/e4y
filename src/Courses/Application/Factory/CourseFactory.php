<?php

namespace App\Courses\Application\Factory;

use App\Common\ValueObject\Money;
use App\CourseLeader\Entity\CourseLeader;
use App\Courses\Application\Exception\DateOfCourseExpiredException;
use App\Courses\Entity\Course;
use DateTime;

class CourseFactory
{
    public static function create(
        CourseLeader $courseLeader,
        string       $name,
        float        $price,
        DateTime     $dateOfCourse
    ): Course
    {
        if ($dateOfCourse->getTimestamp() <= (new DateTime())->getTimestamp()) {
            throw new DateOfCourseExpiredException("Course start date is expire");
        }

        $course = new Course();
        $course->setName($name);
        $course->setPrice(Money::PLNFromFloat($price));
        $course->setDateOfTraining($dateOfCourse);
        $course->setCourseLeader($courseLeader);

        return $course;
    }
}