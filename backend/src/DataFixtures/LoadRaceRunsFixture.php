<?php

namespace App\DataFixtures;

use App\Entity\Mission;
use App\Entity\MissionRegistry;
use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\RaceRun;
use App\Entity\Server;
use App\Entity\Theatre;
use App\Entity\Tour;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LoadRaceRunsFixture extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $planes = $manager->getRepository(Plane::class)->findAll();
        $pilots = $manager->getRepository(Pilot::class)->findAll();
        $tour = $manager->getRepository(Tour::class)->getCurrentTour();
        $theater = new Theatre();
        $theater->setName($faker->name);
        $theater->setNightStart(new \DateTime('00:00:00'));
        $theater->setNightEnd(new \DateTime('23:59:59'));
        $manager->persist($theater);
        $server = new Server();
        $server->setName($faker->company);
        $server->setAddress($faker->ipv4);
        $server->setPort($faker->randomDigit());
        $manager->persist($server);

        $mission = new Mission();
        $mission->setName($faker->company());
        $manager->persist($mission);
        $session = new MissionRegistry();
        $session->setMission($mission);
        $session->setStart($faker->dateTime);
        $session->setEnd($faker->dateTime);
        $session->setTour($tour);
        $session->setServer($server);
        $session->setTheatre($theater);
        $manager->persist($session);

        for ($i = 0; $i < 100;$i++) {
            foreach ($pilots as $pilot) {
                foreach ($planes as $plane) {
                    $race = new RaceRun();
                    $race->setPlane($plane);
                    $race->setPilot($pilot);
                    $race->setTour($tour);
                    $race->setMissionRegistry($session);
                    $race->setTime($faker->numberBetween(100, 200));
                    $race->setServer($server);
                    $manager->persist($race);
                }
            }
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['race_run_fixtures'];
    }
}
