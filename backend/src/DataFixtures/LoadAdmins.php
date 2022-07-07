<?php

namespace App\DataFixtures;

use App\Entity\Pilot;
use App\Repository\BaseUserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoadAdmins extends Fixture implements FixtureGroupInterface
{
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getGroups(): array
    {
        return ['admins_fixtures'];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $pilot = new Pilot();
            $pilot->setName('Admin' . $i);
            $pilot->setSurname('Admin' . $i);
            $pilot->setEnabled(true);
            $pilot->setUsername('admin' . $i);
            $pilot->addRole(BaseUserRepository::ROLE_ADMIN);
            $pilot->setEmail('admin' . $i . '@virpil-servers.com');
            $pilot->setPhone($faker->e164PhoneNumber);
            $pilot->setUcid($faker->uuid);
            $pilot->setCountry($faker->country);
            $password = $this->encoder->hashPassword($pilot, '123456');
            $pilot->setPassword($password);
            $pilot->setAddress($faker->address);

            $manager->persist($pilot);
        }

        for ($i = 1; $i <= 10; $i++) {
            $pilot = new Pilot();
            $pilot->setName('Root' . $i);
            $pilot->setSurname('Root' . $i);
            $pilot->setEnabled(true);
            $pilot->setUsername('root' . $i);
            $pilot->setUcid($faker->uuid);
            $pilot->addRole(BaseUserRepository::ROLE_ROOT);
            $pilot->setEmail('root' . $i . '@virpil-servers.com');
            $pilot->setPhone('+1234567890');
            $pilot->setCountry($faker->country);
            $password = $this->encoder->hashPassword($pilot, '123456');
            $pilot->setPassword($password);
            $pilot->setAddress($i . ' Fake Street, USA');

            $manager->persist($pilot);
        }

        $manager->flush();
    }
}
