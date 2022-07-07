<?php

namespace App\DataFixtures;

use App\Entity\WeatherLimit;
use App\Repository\WeatherLimitRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class LoadWeatherLimits extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $limits = WeatherLimitRepository::$limits;

        foreach ($limits as $row) {
            $limit = new WeatherLimit();
            $limit->setTitle($row['title']);
            $limit->setCloudsBaseDay($row['cloudsBaseDay']);
            $limit->setCloudsBaseNight($row['cloudsBaseNight']);
            $limit->setVisibilityDay($row['visibilityDay']);
            $limit->setVisibilityNight($row['visibilityNight']);

            $manager->persist($limit);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['weather_limits_fixtures'];
    }
}
