<?php

namespace App\Controller\System\Crud;

use App\Entity\Plane;
use App\Entity\WeatherLimit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class WeatherLimitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return WeatherLimit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            IntegerField::new('cloudsBaseDay'),
            IntegerField::new('cloudsBaseNight'),
            IntegerField::new('visibilityDay'),
            IntegerField::new('visibilityNight'),
        ];
    }
}
