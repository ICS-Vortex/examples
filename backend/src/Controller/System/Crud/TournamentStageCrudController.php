<?php

namespace App\Controller\System\Crud;

use App\Entity\TournamentStage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TournamentStageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TournamentStage::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('title')
            ->add('titleEn')
            ->add('code')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('hidden'),
            TextField::new('title'),
            TextField::new('titleEn'),
            AssociationField::new('tournament'),
            TextField::new('code'),
            DateTimeField::new('start'),
            DateTimeField::new('end'),
            NumberField::new('timeQuota'),
            IntegerField::new('position'),
            IntegerField::new('winners'),
            AssociationField::new('participants'),
        ];
    }
}
