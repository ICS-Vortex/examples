<?php

namespace App\DataFixtures;

use App\Entity\Airfield;
use App\Entity\Dogfight;
use App\Entity\Elo;
use App\Entity\Mission;
use App\Entity\MissionRegistry;
use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\Server;
use App\Entity\Sortie;
use App\Entity\Theatre;
use App\Entity\Tour;
use App\Repository\SortieRepository;
use DateInterval;
use DatePeriod;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LoadSorties extends Fixture implements FixtureGroupInterface
{
    public const SERVER_REFERENCE = 'server';
    public const TOURS_REFERENCE = 'tours';
    public const AIRFIELD_REFERENCE = 'airfield';

    public static function getGroups(): array
    {
        return ['sorties_fixtures'];
    }

    public function load(ObjectManager $manager)
    {

        $faker = Factory::create();
        $missionRegistries = $this->generateData($manager);
        $victims = $pilots = $manager->getRepository(Pilot::class)->findBy([], [], 2);
        $planes = $manager->getRepository(Plane::class)->findAll([], [], 5);
        $sides = ['RED', 'BLUE'];
        foreach ($sides as $side => $victimSide) {
            foreach ($pilots as $pilot) {
                foreach ($planes as $plane) {
                    /** @var MissionRegistry $mr */
                    foreach ($missionRegistries as $mr) {
                        $tour = $mr->getTour();
                        $start = new DateTime(
                            $faker->dateTimeBetween(
                                $tour->getStart()->format('Y-m-d H:i:s'),
                                $tour->getEnd()->format('Y-m-d H:i:s'))->format('Y-m-d H:i:s'));

                        $end = $start->add(new DateInterval('PT1H'));
                        $total = strtotime($end->format('Y-m-d H:i:s')) - strtotime($start->format('Y-m-d H:i:s'));
                        $sortie = new Sortie();
                        $sortie->setStartFlight($start);
                        $sortie->setEndFlight($end);
                        $sortie->setPilot($pilot);
                        $sortie->setPlane($plane);
                        $sortie->setSide($side);
                        $sortie->setRegisteredMission($mr);
                        $sortie->setTour($tour);
                        $sortie->setTheatre($mr->getTheatre());
                        $sortie->setStatus(SortieRepository::STATUS_AIRFIELD);
                        $sortie->setServer($mr->getServer());
                        $sortie->setTakeoffFrom($this->getReference(self::AIRFIELD_REFERENCE));
                        $sortie->setLandingAt($this->getReference(self::AIRFIELD_REFERENCE));
                        $sortie->setTotalTime($total);

                        $manager->persist($sortie);
                        $manager->flush();
                    }

                }
            }
        }
    }

    public function generateData(ObjectManager $manager)
    {
        $faker = Factory::create();
        $tours = $this->loadTours($manager);
        $date = $faker->dateTime();

        $server = new Server();
        $server->setAddress($faker->ipv4);
        $server->setName($faker->company);
        $manager->persist($server);
        $manager->flush();

        $this->setReference(self::SERVER_REFERENCE, $server);

        $airfield = new Airfield();
        $airfield->setTitle($faker->company);
        $manager->persist($airfield);
        $manager->flush();

        $this->setReference(self::AIRFIELD_REFERENCE, $airfield);

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
