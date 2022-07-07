<?php

namespace App\Controller\System\Crud;

use App\Entity\RaceType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RaceTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RaceType::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('type'),
            TextField::new('title'),
            TextField::new('titleEn'),
            IntegerField::new('position'),
        ];
    }
}
