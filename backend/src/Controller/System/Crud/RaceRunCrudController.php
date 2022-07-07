<?php

namespace App\Controller\System\Crud;

use App\Entity\RaceRun;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class RaceRunCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceRun::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('tournament')
            ->add('stage')
            ->add('pilot')
            ->add('plane')
            ->add('server');
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('tournament'),
            AssociationField::new('missionRegistry'),
            AssociationField::new('aircraftClass'),
            AssociationField::new('tour'),
            AssociationField::new('stage'),
            AssociationField::new('pilot'),
            AssociationField::new('plane'),
            NumberField::new('time'),
            AssociationField::new('server'),
            DateTimeField::new('raceTime'),
        ];
    }
}
