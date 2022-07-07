<?php

namespace App\Controller\System\Crud;

use App\Entity\AircraftClass;
use App\Field\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class AircraftClassCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return AircraftClass::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];
        $fields[] = TextField::new('code');
        $fields[] = TextField::new('title');
        $fields[] = TextField::new('titleEn');
        if ($pageName === Crud::PAGE_INDEX) {
            $fields[] = ImageField::new('image')
                ->setBasePath($this->getParameter('app.path.aircrafts_classes_images'));
        }
        $fields[] = AssociationField::new('aircrafts');
        if ($pageName === Crud::PAGE_EDIT || $pageName === Crud::PAGE_NEW) {
            $fields[] = VichImageField::new('imageFile');
        }
        return $fields;
    }
}
