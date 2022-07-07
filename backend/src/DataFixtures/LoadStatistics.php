<?php

namespace App\DataFixtures;

use App\Entity\Airfield;
use App\Entity\CurrentKill;
use App\Entity\Dogfight;
use App\Entity\Elo;
use App\Entity\Kill;
use App\Entity\Mission;
use App\Entity\MissionRegistry;
use App\Entity\Pilot;
use App\Entity\Plane;
use App\Entity\Server;
use App\Entity\Sortie;
use App\Entity\Tour;
use App\Entity\Unit;
use App\Repository\CurrentKillRepository;
use App\Repository\SortieRepository;
use DateInterval;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LoadStatistics extends Fixture
{
    public function load(ObjectManager $manager)
    {

//        $faker = Factory::create();
//        $sides = ['RED', 'BLUE'];
//        $airfield = new Airfield();
//        $airfield->setTitle('Batumi');
//        $manager->persist($airfield);
//        $tour = new Tour();
//        $tourStart = new \DateTime();
//        $tour->setStart($tourStart);
//        $tour->setEnd($tourStart->add(new DateInterval('P30D')));
//        $tour->setTitle($faker->userName);
//        $tour->setTitleEn($faker->userName);
//        $tour->setFinished(false);
//        $missions = $manager->getRepository(Mission::class)->findAll();
//        $manager->persist($tour);
//        $manager->flush();
//        $servers = $manager->getRepository(Server::class)->findAll();
//        $pilots = $manager->getRepository(Pilot::class)->findAll();
//        $victims = $manager->getRepository(Pilot::class)->findAll();
//        $planes = $manager->getRepository(Plane::class)->findAll();
//        $sams = $manager->getRepository(Unit::class)->findBy([
//            'groundUnit' => true
//        ]);
//        /** @var Server $server */
//        /** @var Mission $mission */
//        /** @var Pilot $victim */
//        /** @var Pilot $pilot */
//        /** @var Plane $plane */
//        /** @var Unit $sam */
//        foreach ($missions as $mission) {
//            foreach ($servers as $server) {
//                for ($i = 0; $i < 5; $i++) {
//                    $start = $faker->dateTimeBetween(
//                        $tour->getStart()->format('Y-m-d H:i:s'),
//                        $tour->getEnd()->format('Y-m-d H:i:s'),
//                    );
//                    $registry = new MissionRegistry();
//                    $registry->setServer($server);
//                    $registry->setStart($start);
//                    $registry->setEnd($start->add(new DateInterval('PT3H')));
//                    $registry->setTour($tour);
//                    $registry->setFinished(true);
//                    $registry->setWinner(MissionRegistry::DRAW);
//                    $registry->setMission($mission);
//                    $manager->persist($registry);
//
//                    foreach ($pilots as $pilot) {
//                        foreach ($planes as $plane) {
//                            foreach ($sides as $side) {
//                                for ($j = 0; $j < 3; $j++) {
//                                    $flightStart = $faker->dateTimeBetween(
//                                        $registry->getStart()->format('Y-m-d H:i:s'),
//                                        $registry->getEnd()->format('Y-m-d H:i:s'),
//                                    );
//                                    $sortie = new Sortie();
//                                    $sortie->setRegisteredMission($registry);
//                                    $sortie->setTour($tour);
//                                    $sortie->setServer($server);
//                                    $sortie->setPilot($pilot);
//                                    $sortie->setPlane($plane);
//                                    $sortie->setSide($side);
//                                    $sortie->setStatus(SortieRepository::STATUS_AIRFIELD);
//                                    $sortie->setStartFlight($flightStart);
//                                    $sortie->setEndFlight($flightStart->add(new DateInterval('P35M')));
//                                    $totalTime = strtotime($sortie->getEndFlight()->format('Y-m-d H:i:s'))
//                                        - strtotime($sortie->getStartFlight()->format('Y-m-d H:i:s'));
//                                    $sortie->setTotalTime($faker->randomDigit);
//                                    $sortie->setTakeoffFrom($airfield);
//                                    $sortie->setLandingAt($airfield);
//                                    $manager->persist($sortie);
//                                }
//                                foreach ($sams as $sam) {
//                                    $currentKill = new CurrentKill();
//                                    $currentKill->setSide($side);
//                                    $currentKill->setVictimSide($side === 'RED' ? 'BLUE' : 'RED');
//                                    $currentKill->setUnit($sam);
//                                    $currentKill->setPoints($faker->randomDigit);
//                                    $currentKill->setKillTime(
//                                        $faker->dateTimeBetween(
//                                            $registry->getStart()->format('Y-m-d H:i:s'),
//                                            $registry->getEnd()->format('Y-m-d H:i:s'),
//                                        ));
//                                    $currentKill->setPlane($plane);
//                                    $currentKill->setPilot($pilot);
//                                    $currentKill->setServer($server);
//                                    $currentKill->setTour($tour);
//                                    $currentKill->setRegisteredMission($registry);
//                                    $currentKill->setIsAi(true);
//                                    $currentKill->setFriendlyFire(false);
//                                    $currentKill->setAction(CurrentKillRepository::ACTION_DESTROYED);
//                                    $manager->persist($currentKill);
//                                }
//
//                                foreach ($victims as $victim) {
//                                    $dogfight = new Dogfight();
//                                    $dogfight->setTour($tour);
//                                    $dogfight->setServer($server);
//                                    $dogfight->setFriendly($pilot === $victim);
//                                    $dogfight->setSide($side);
//                                    $dogfight->setPilot($pilot);
//                                    $dogfight->setPlane($plane);
//                                    $dogfight->setVictim($victim);
//                                    $dogfight->setVictimSide($dogfight->getSide() === 'RED' ? 'BLUE' : 'RED');
//                                    $dogfight->setVictimPlane($plane);
//                                    $dogfight->setRegisteredMission($registry);
//                                    $dogfight->setKillTime($faker->dateTimeBetween(
//                                        $registry->getStart()->format('Y-m-d H:i:s'),
//                                        $registry->getEnd()->format('Y-m-d H:i:s'),
//                                    ));
//                                    $dogfight->setPoints($faker->randomDigit);
//                                    $manager->persist($dogfight);
//
//                                    $elo = new Elo();
//                                    $elo->setServer($server);
//                                    $elo->setTour($tour);
//                                    $elo->setDogfight($dogfight);
//                                    $elo->setPilot($pilot);
//
//                                    $manager->persist($elo);
//                                }
//
//                                foreach ($sams as $sam) {
//                                    $kill = new Kill();
//                                    $kill->setTour($tour);
//                                    $kill->setServer($server);
//                                    $kill->setFriendly(false);
//                                    $kill->setPilot($pilot);
//                                    $kill->setPlane($plane);
//                                    $kill->setUnit($sam);
//                                    $kill->setSide($side === 'RED' ? 'BLUE' : 'RED');
//                                    $kill->setRegisteredMission($registry);
//                                    $kill->setKillTime($faker->dateTimeBetween(
//                                        $registry->getStart()->format('Y-m-d H:i:s'),
//                                        $registry->getEnd()->format('Y-m-d H:i:s'),
//                                    ));
//                                    $kill->setPoints($faker->randomDigit);
//                                    $manager->persist($kill);
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//        }
        $manager->flush();
    }
}
