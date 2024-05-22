<?php

namespace App\Szkolenia\Application\Factory;

use App\Entity\Training;
use App\Szkolenia\Domain\CourseToDisplayCollection;

class CourseToDisplayCollectionFactory
{
    public function __construct(
        private CourseToDisplayFactory $courseToDisplayFactory
    )
    {}

    /** @param Training[] $courses */
    public function createCollection(array $courses): CourseToDisplayCollection
    {
        $collection = new CourseToDisplayCollection();
        foreach ($courses as $course) {
            $collection->addCourse($this->courseToDisplayFactory->create($course));
        }

        return $collection;
    }
}