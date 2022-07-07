<?php

namespace App\Controller\System\Crud;

use App\Entity\Ban;
use App\Entity\GameDevice;
use App\Field\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class GameDeviceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GameDevice::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];
        $fields[] = TextField::new('name');
        if ($pageName === Action::INDEX) {
            $fields[] = ImageField::new('image')->setBasePath($this->getParameter('app.path.devices_images'));
        } else {
            $fields[] =  VichImageField::new('imageFile');
        }
        return $fields;
    }
}
