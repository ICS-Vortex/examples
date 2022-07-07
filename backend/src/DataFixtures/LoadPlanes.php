<?php

namespace App\DataFixtures;

use App\Entity\Plane;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LoadPlanes extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $planes = [
            'A-10A',
            'A-10C',
            'Su-25T',
            'Su-27',
            'Su-33',
            'FA-18C',
            'F-15C',
            'F-16C',
            'P-47D-30',
            'F-5E',
        ];

        foreach ($planes as $name) {
            $plane = new Plane();
            $plane->setName($name);
            $plane->setMod(false);
            $plane->setDescription($faker->text(200));
            $manager->persist($plane);
        }

        $manager->flush();
    }
}
