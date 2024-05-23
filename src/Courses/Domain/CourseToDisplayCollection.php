<?php

namespace App\Courses\Domain;

class CourseToDisplayCollection
{
    /** @var CourseToDisplay[] */
    private array $courses;

    public function addCourse(CourseToDisplay $courseToDisplay): void
    {
        $this->courses[$courseToDisplay->getCourseId()] = $courseToDisplay;
    }

    public function removeCourse(CourseToDisplay $courseToDisplay): void
    {
        unset($this->courses[$courseToDisplay->getCourseId()]);
    }

    public function getCoursesCount(): int
    {
        return count($this->courses);
    }

    public function toArray(): array
    {
        return [
            'coursesCount' => $this->getCoursesCount(),
            'courses' => array_map(function (CourseToDisplay $courseToDisplay) {
                return $courseToDisplay->toArray();
            }, $this->courses),
        ];
    }
}