<?php

namespace App\Szkolenia\Application\Command;

use DateTimeInterface;

class GetCourseListingCommand
{
    public function __construct(
        private ?string            $name = null,
        private ?DateTimeInterface $startDate = null,
        private ?DateTimeInterface $endDate = null,
        private ?string            $courseLeaderName = null,
        private ?string            $courseLeaderSurname = null
    ) {}

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function getCourseLeaderName(): ?string
    {
        return $this->courseLeaderName;
    }

    public function getCourseLeaderSurname(): ?string
    {
        return $this->courseLeaderSurname;
    }
}