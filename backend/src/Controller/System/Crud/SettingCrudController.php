<?php

namespace App\Controller\System\Crud;

use App\Entity\Setting;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SettingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Setting::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];
        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL) {
            $fields[] = TextField::new('name')
                ->setTemplatePath('_layout/setting_template.html.twig');
            $fields[] = TextField::new('keyword');
        }
        $fields[] = TextField::new('value');

        if ($pageName === Crud::PAGE_DETAIL) {
            $fields[] = TextField::new('defaultValue');
            $fields[] = TextField::new('description');
        }

        return $fields;
    }
}
