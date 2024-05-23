<?php

namespace App\DataFixtures;

use App\CourseLeader\Entity\CourseLeader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CourseLeaderFixture extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['dev'];
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->data() as $name => $data) {
            $animal = new CourseLeader();
            $animal->setId($data['id']);
            $animal->setName($data['name']);
            $animal->setSurname($data['surname']);

            $manager->persist($animal);
            $this->setReference($name, $animal);
        }

        $manager->flush();
    }

    private function data(): array
    {
        return [
            'zenek.benbenek-leoniak' => [
                'id' => 1,
                'name' => 'Zenek',
                'surname' => 'Benbenek-Leoniak',
            ],
            'zenek.benbenek' => [
                'id' => 2,
                'name' => 'Zenek',
                'surname' => 'Benbenek',
            ],
            'rafal.rafalski' => [
                'id' => 3,
                'name' => 'Rafał',
                'surname' => 'Rafalski',
            ],
            'daniel.kowalski' => [
                'id' => 4,
                'name' => 'Daniel',
                'surname' => 'Kowalski',
            ]
        ];
    }
}