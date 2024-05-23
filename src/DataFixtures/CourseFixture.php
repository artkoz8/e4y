<?php

namespace App\DataFixtures;

use App\Common\ValueObject\Money;
use App\Courses\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CourseFixture extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['dev'];
    }

    public function getDependencies(): array
    {
        return [
            CourseLeaderFixture::class
        ];
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->data() as $name => $data) {
            $course = new Course();
            $course->setId($data['id']);
            $course->setName($data['name']);
            $course->setCourseLeader($data['courseLeader']);
            $course->setPrice(Money::PLNFromFloat($data['price']));
            $course->setDateOfTraining($data['dateOfTraining']);

            $manager->persist($course);
            $this->setReference($name, $course);
        }

        $manager->flush();
    }

    private function data(): array
    {
        return [
            'szkolenie z testów' => [
                'id' => 1,
                'name' => 'szkolenie z testów',
                'courseLeader' => $this->getReference('zenek.benbenek'),
                'price' => 10.39,
                'dateOfTraining' => new \DateTime('2029-01-01 11:00:00'),
            ],
            'szkolenie z hr' => [
                'id' => 2,
                'name' => 'szkolenie z hr',
                'courseLeader' => $this->getReference('zenek.benbenek-leoniak'),
                'price' => 999.99,
                'dateOfTraining' => new \DateTime('2028-01-01 11:00:00'),
            ],
            'szkolenie z word' => [
                'id' => 3,
                'name' => 'szkolenie z word',
                'courseLeader' => $this->getReference('zenek.benbenek'),
                'price' => 999.99,
                'dateOfTraining' => new \DateTime('2028-01-01 11:00:00'),
            ],
            'szkolenie z scrum' => [
                'id' => 4,
                'name' => 'szkolenie z scrum',
                'courseLeader' => $this->getReference('zenek.benbenek'),
                'price' => 999.99,
                'dateOfTraining' => new \DateTime('2028-11-01 11:00:00'),
            ],
            'szkolenie z scrum master' => [
                'id' => 5,
                'name' => 'szkolenie z scrum master',
                'courseLeader' => $this->getReference('zenek.benbenek-leoniak'),
                'price' => 449.89,
                'dateOfTraining' => new \DateTime('2026-11-01 11:00:00'),
            ],
            'szkolenie z scrum master' => [
                'id' => 6,
                'name' => 'szkolenie z ksiegowosci',
                'courseLeader' => $this->getReference('daniel.kowalski'),
                'price' => 1249.89,
                'dateOfTraining' => new \DateTime('2025-06-09 12:00:00'),
            ]
        ];
    }
}