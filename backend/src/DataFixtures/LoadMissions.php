<?php

namespace App\DataFixtures;

use App\Entity\Instance;
use App\Entity\Mission;
use App\Entity\Server;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LoadMissions extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        $server = new Server();
        $server->setName($faker->company);
        $server->setAddress($faker->ipv4);
        $server->setPort(10308);
        $server->setActive(true);
        $server->setShowMap(true);
        $server->setReportsLocation('A:\\');
        $server->setEmail($faker->email);
        $manager->persist($server);

        for ($i = 0; $i < 5; $i++) {
            $mission = new Mission();
            $mission->setName($faker->name);
            $mission->setDescription($faker->text);
            $mission->setDescriptionEn($faker->text(200));
            $mission->setIsEvent(false);
            $mission->setServer($server);
            $manager->persist($mission);
        }

        $manager->flush();
    }
}
