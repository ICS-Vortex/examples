<?php

namespace App\DataFixtures;

use App\Entity\SocialLink;
use App\Repository\SocialLinkRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class LoadSocialIcons extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $icons = SocialLinkRepository::$icons;
        foreach ($icons as $title => $icon) {
            $link = new SocialLink();
            $link->setNewWindow(true);
            $link->setIcon($icon);
            $link->setUrl($faker->url);
            $manager->persist($link);
        }
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['icons_fixtures'];
    }
}
