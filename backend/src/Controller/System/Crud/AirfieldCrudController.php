<?php

namespace App\Controller\System\Crud;

use App\Entity\Airfield;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AirfieldCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Airfield::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('tcn'),
            TextField::new('ils'),
            TextField::new('latitude'),
            TextField::new('longitude'),
            TextField::new('longitude'),
            TextField::new('elevationFeet'),
            TextField::new('elevationMeters'),
            TextField::new('atc'),
            TextEditorField::new('description'),
        ];
    }
}
