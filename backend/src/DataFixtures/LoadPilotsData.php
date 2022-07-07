<?php

namespace App\DataFixtures;

use App\Entity\Pilot;
use App\Repository\BaseUserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\PdoSessionHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoadPilotsData extends Fixture
{
    /**
     * @var Connection
     */
    private Connection $connection;

    /**
     * @var PdoSessionHandler
     */
    private PdoSessionHandler $pdoSessionHandler;
    /**
     * @var UserPasswordHasherInterface
     */
    private UserPasswordHasherInterface $encoder;

    public function __construct(Connection $connection, UserPasswordHasherInterface $encoder, PdoSessionHandler $pdoSessionHandler)
    {
        $this->pdoSessionHandler = $pdoSessionHandler;
        $this->connection = $connection;
        $this->encoder = $encoder;
    }

    /**
     * @return Connection
     */
    public function getConnection(): Connection
    {
        return $this->connection;
    }

    /**
     * @param Connection $connection
     */
    public function setConnection(Connection $connection): void
    {
        $this->connection = $connection;
    }

    /**
     * @return UserPasswordHasherInterface
     */
    public function getEncoder(): UserPasswordHasherInterface
    {
        return $this->encoder;
    }

    /**
     * @param UserPasswordHasherInterface $encoder
     */
    public function setEncoder(UserPasswordHasherInterface $encoder): void
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager){
        $encoder = $this->getEncoder();
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $pilot = new Pilot();
            $password = $encoder->hashPassword($pilot, '1921680100');
            $pilot->setUsername($faker->userName);
            $pilot->setEmail($faker->email);
            $pilot->setName($faker->firstName);
            $pilot->setSurname($faker->lastName);
            $pilot->setUcid($faker->uuid);
            $pilot->setChecked(true);
            $pilot->setEnabled(true);
            $pilot->addRole(BaseUserRepository::ROLE_USER);
            $pilot->setPhone($faker->phoneNumber);
            $pilot->setPassword($password);
            $pilot->setAddress($faker->address);

            $manager->persist($pilot);
        }
        $manager->flush();
    }
}
