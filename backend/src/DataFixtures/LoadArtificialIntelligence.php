<?php

namespace App\DataFixtures;

use App\Entity\Unit;
use App\Entity\UnitType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LoadArtificialIntelligence extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $planes = [
            'A-10A',
            'A-10C',
            'Su-25T',
            'Su-27',
            'Su-33',
            'FA-18C',
            'F-15C',
            'F-16C',
            'P-47D-30',
            'F-5E',
        ];

        $sams = [
            'ЗРК Оса',
            'ЗРК Бук',
            'ЗРК Куб',
            'ЗРК С-300',
            'ЗРК С-125',
        ];

        $categoryPlanes = new UnitType();
        $categorySams = new UnitType();
        $categoryPlanes->setTitle('Planes');
        $categorySams->setTitle('SAM');
        $manager->persist($categoryPlanes);
        $manager->persist($categorySams);

        foreach ($planes as $plane) {
            $unit = new Unit();
            $unit->setName($plane);
            $unit->setAirUnit(true);
            $unit->setType($categoryPlanes);
            $manager->persist($unit);
        }

        foreach ($sams as $sam) {
            $unit = new Unit();
            $unit->setName($sam);
            $unit->setGroundUnit(true);
            $unit->setType($categorySams);
            $manager->persist($unit);
        }

        $manager->flush();
    }
}
