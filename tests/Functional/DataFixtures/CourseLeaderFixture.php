<?php

namespace App\Tests\Functional\DataFixtures;

use App\Entity\CourseLeader;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CourseLeaderFixture extends Fixture //implements DependentFixtureInterface
{

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
            ]
        ];
    }
}