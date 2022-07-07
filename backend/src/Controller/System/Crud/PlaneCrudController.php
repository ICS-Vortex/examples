<?php

namespace App\Controller\System\Crud;

use App\Entity\Plane;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PlaneCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Plane::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('mod'),
            BooleanField::new('helicopter'),
            TextField::new('name'),
            AssociationField::new('weatherLimit'),
            TextEditorField::new('description'),
        ];
    }
}
