<?php

namespace App\DataFixtures;

use App\Entity\FeaturedVideo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class LoadFeaturedVideo extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $videos = [
            'eWN5R7tRMws' => 'https://www.youtube.com/watch?v=eWN5R7tRMws',
            'RlmUWO2JL6I' => 'https://www.youtube.com/watch?v=RlmUWO2JL6I',
            'ZzVW9315ur0' => 'https://www.youtube.com/watch?v=ZzVW9315ur0',
        ];

        foreach ($videos as $code => $url) {
            $video = new FeaturedVideo();
            $video->setCode($code);
            $video->setUrl($url);
            $manager->persist($video);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['featured_videos_fixtures'];
    }
}
