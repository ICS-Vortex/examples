<?php

namespace App\Controller\System\Crud;

use App\Entity\SenderUpdate;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class SenderUpdateCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SenderUpdate::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            NumberField::new('version'),
            TextEditorField::new('notes'),
        ];

        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL) {
            array_unshift($fields, UrlField::new('exe')
                ->setTemplatePath('_layout/file_template.html.twig'));
        } else {
            array_unshift($fields, ImageField::new('exeFile'));
        }

        return $fields;
    }
}
