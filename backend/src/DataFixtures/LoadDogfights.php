<?php

namespace App\DataFixtures;

use App\Entity\Dogfight;
use App\Entity\Mission;
use App\Entity\MissionRegistry;
use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\Server;
use App\Entity\Theatre;
use App\Entity\Tour;
use DateInterval;
use DatePeriod;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LoadDogfights extends Fixture implements FixtureGroupInterface
{
    public const SERVER_REFERENCE = 'server';
    public const TOURS_REFERENCE = 'tours';

    public static function getGroups(): array
    {
        return ['dogfights_fixtures'];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $missionRegistries = $this->generateData($manager);
        $victims = $pilots = $manager->getRepository(Pilot::class)->findBy([], [], 2);
        $planes = $victimsPlanes = $manager->getRepository(Plane::class)->findAll([], [], 5);
        $sides = ['RED' => 'BLUE', 'BLUE' => 'RED'];
        foreach ($sides as $side => $victimSide) {
            foreach ($pilots as $pilot) {
                foreach ($victims as $victim) {
                    if ($victim === $pilot) continue;
                    foreach ($planes as $plane) {
                        foreach ($victimsPlanes as $victimPlane) {
                            if ($plane === $victimPlane) continue;
                            /** @var MissionRegistry $mr */
                            foreach ($missionRegistries as $mr) {
                                $dogfight = new Dogfight();
                                $dogfight->setKillTime(
                                    new DateTime(
                                        $faker->dateTimeBetween(
                                            $mr->getStart()->format('Y-m-d H:i:s'),
                                            $mr->getEnd()->format('Y-m-d H:i:s'))->format('Y-m-d H:i:s')
                                    )
                                );
                                $dogfight->setTour($mr->getTour());
                                $dogfight->setServer($mr->getServer());
                                $dogfight->setInAir(true);
                                $dogfight->setPvp(true);
                                $dogfight->setEloCalculated(true);
                                $dogfight->setFriendly(false);
                                $dogfight->setPilot($pilot);
                                $dogfight->setPlane($plane);
                                $dogfight->setPoints($faker->randomNumber(2));
                                $dogfight->setRegisteredMission($mr);
                                $dogfight->setSide($side);
                                $dogfight->setVictim($victim);
                                $dogfight->setVictimPlane($victimPlane);
                                $dogfight->setVictimSide($victimSide);
                                $manager->persist($dogfight);
                                $manager->flush();

                                //$manager->getRepository(Elo::class)->calculateElo($dogfight);
                            }
                        }
                    }
                }
            }
        }
    }

    public function generateData(ObjectManager $manager)
    {
        $faker = Factory::create();
        $tours = $this->loadTours($manager);

        $server = new Server();
        $server->setAddress($faker->ipv4);
        $server->setName($faker->company);
        $manager->persist($server);
        $manager->flush();

        $this->setReference(self::SERVER_REFERENCE, $server);

        $theatre = new Theatre();
        $theatre->setName($faker->country);
        $theatre->setNightStart(new DateTime('21:00:00'));
        $theatre->setNightEnd(new DateTime('06:00:00'));
        $manager->persist($theatre);
        $manager->flush();

        $mission = new Mission();
        $mission->setName($faker->title);
        $mission->setIsEvent(false);
        $mission->setTheatre($theatre);
        $manager->persist($mission);
        $manager->flush();

        $missionRegistries = [];

        /** @var Tour $tour */
        foreach ($tours as $tour) {
            $start = new DateTime(
                $faker->dateTimeBetween(
                    $tour->getStart()->format('Y-m-d H:i:s'),
                    $tour->getEnd()->format('Y-m-d H:i:s'))->format('Y-m-d H:i:s'));

            $end = $start->add(new DateInterval('PT10H'));
            $mr = new MissionRegistry();
            $mr->setServer($server);
            $mr->setTheatre($theatre);
            $mr->setStart($start);
            $mr->setEnd($end);
            $mr->setFinished(true);
            $mr->setMission($mission);
            $mr->setTour($tour);
            $mr->setWinner('DRAW');
            $manager->persist($mr);
            $missionRegistries[] = $mr;
        }

        $manager->flush();
        return $missionRegistries;
    }

    private function loadTours(ObjectManager $manager)
    {
        $faker = Factory::create();
        $tours = [];
        $start = new DateTime(date('Y-01-01 00:00:00'));
        $interval = new DateInterval('P1M');
        $end = new DateTime(date('Y-12-01 00:00:00'));
        $period = new DatePeriod($start, $interval, $end);
        foreach ($period as $month) {
            $tour = new Tour();
            $tour->setStart(new DateTime($month->format('Y-m-01 00:00:00')));
            $tour->setEnd(new DateTime($month->format('Y-m-t 23:59:59')));
            $tour->setFinished(true);
            $tour->setTitle($faker->company);
            $tour->setTitleEn($faker->company);
            $manager->persist($tour);

            $tours[] = $tour;
        }
        return $tours;
    }
}
