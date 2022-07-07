<?php

namespace App\DataFixtures;

use App\Entity\FeaturedVideo;
use App\Entity\Instance;
use App\Entity\Server;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LoadServers extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $videos = [
            'eWN5R7tRMws' => 'https://www.youtube.com/watch?v=eWN5R7tRMws',
            'RlmUWO2JL6I' => 'https://www.youtube.com/watch?v=RlmUWO2JL6I',
            'ZzVW9315ur0' => 'https://www.youtube.com/watch?v=ZzVW9315ur0',
        ];
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $server = new Server();
            $server->setName($faker->company);
            $server->setAddress($faker->ipv4);
            $server->setTeamSpeakAddress($faker->ipv4);
            $server->setSrsAddress($faker->ipv4);
            $server->setPort(10308);
            $server->setActive(true);
            $server->setShowMap(true);
            $server->setReportsLocation('A:\\');
            $server->setEmail($faker->email);
            $manager->persist($server);

            foreach ($videos as $url => $code) {
                $video = new FeaturedVideo();
                $video->setCode($code);
                $video->setUrl($url);
                $video->setServer($server);
                $manager->persist($video);
            }
        }

        $manager->flush();
    }
}
