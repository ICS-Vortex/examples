<?php

namespace App\Controller\System\Crud;

use App\Entity\Theatre;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TimeField;

class TheatreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Theatre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TimeField::new('nightStart'),
            TimeField::new('nightEnd'),
        ];
    }
}
