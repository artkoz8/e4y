<?php

namespace App\Courses\Entity;

use App\Common\ValueObject\Money;
use App\CourseLeader\Entity\CourseLeader;
use App\Courses\Repository\CourseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 512, unique: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateOfTraining = null;

    #[ORM\Embedded(class: Money::class)]
    private ?Money $price = null;

    #[ORM\ManyToOne(inversedBy: 'training')]
    private ?CourseLeader $courseLeader = null;

    public function setId(?int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDateOfTraining(): ?\DateTimeInterface
    {
        return $this->dateOfTraining;
    }

    public function setDateOfTraining(\DateTimeInterface $dateOfTraining): static
    {
        $this->dateOfTraining = $dateOfTraining;

        return $this;
    }

    public function getPrice(): ?Money
    {
        return $this->price;
    }

    public function setPrice(Money $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCourseLeader(): ?CourseLeader
    {
        return $this->courseLeader;
    }

    public function setCourseLeader(?CourseLeader $courseLeader): static
    {
        $this->courseLeader = $courseLeader;

        return $this;
    }
}
