<?php

namespace App\Controller\System\Crud;

use App\Entity\UnitType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UnitTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UnitType::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
