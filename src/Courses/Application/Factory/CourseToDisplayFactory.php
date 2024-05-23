<?php

namespace App\Courses\Application\Factory;

use App\Entity\Training;
use App\Courses\Application\Service\CalculateCourseDiscountService;
use App\Courses\Domain\CourseToDisplay;

class CourseToDisplayFactory
{
    public function __construct(
        private readonly CalculateCourseDiscountService $calculateCourseDiscountService
    ) {}

    public function create(Training $course): CourseToDisplay
    {
        $discount = $this->calculateCourseDiscountService->calculateDiscount($course);
        $price = $course->getPrice()->subtraction($discount);

        return new CourseToDisplay(
            courseId: $course->getId(),
            name: $course->getName(),
            price: $price,
            courseLider: $course->getCourseLeader(),
            dateOfCourse: $course->getDateOfTraining()
        );
    }
}