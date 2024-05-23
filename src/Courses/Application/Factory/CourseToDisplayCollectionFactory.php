<?php

namespace App\Courses\Application\Factory;

use App\Courses\Domain\CourseToDisplayCollection;
use App\Courses\Entity\Course;

class CourseToDisplayCollectionFactory
{
    public function __construct(
        private CourseToDisplayFactory $courseToDisplayFactory
    )
    {}

    /** @param Course[] $courses */
    public function createCollection(array $courses): CourseToDisplayCollection
    {
        $collection = new CourseToDisplayCollection();
        foreach ($courses as $course) {
            $collection->addCourse($this->courseToDisplayFactory->create($course));
        }

        return $collection;
    }
}