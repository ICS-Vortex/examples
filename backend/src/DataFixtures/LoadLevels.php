<?php

namespace App\DataFixtures;

use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class LoadLevels extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['level_fixtures'];
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 2000; $i++) {
            $level = new Level();
            $level->setLevel($i);
            $level->setPoints($i * 100);
            $manager->persist($level);
        }
        $manager->flush();
    }
}
