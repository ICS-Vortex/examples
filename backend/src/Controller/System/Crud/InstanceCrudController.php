<?php

namespace App\Controller\System\Crud;

use App\Entity\Instance;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class InstanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Instance::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('serialNumber'),
            AssociationField::new('servers')->autocomplete(),
            BooleanField::new('enabled'),
        ];
    }
}
