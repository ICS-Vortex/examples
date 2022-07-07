<?php

namespace App\Controller\System\Crud;

use App\Entity\Tour;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TourCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tour::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('start'),
            DateTimeField::new('end'),
            TextField::new('title'),
            TextField::new('titleEn'),
            BooleanField::new('finished')->renderAsSwitch(false),
        ];
    }
}
