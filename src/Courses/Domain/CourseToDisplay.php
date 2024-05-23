<?php

namespace App\Courses\Domain;

use App\Common\ValueObject\Money;
use App\CourseLeader\Entity\CourseLeader;
use DateTimeInterface;

class CourseToDisplay
{
    public function __construct(
        private int $courseId,
        private string $name,
        private CourseLeader $courseLider,
        private Money $price,
        private DateTimeInterface $dateOfCourse
    ) {}

    public function getCourseId(): int
    {
        return $this->courseId;
    }

    public function toArray(): array
    {
        return [
            'courseId' => $this->courseId,
            'name' => $this->name,
            'courseLeader' => (string) $this->courseLider,
            'price' => [
                'amount' => $this->price->getAmountFloat(),
                'currency' => $this->price->getCurrency(),
            ],
            'dateOfCourse' => $this->dateOfCourse->format('Y-m-d H:i'),
        ];
    }
}