<?php

namespace App\Controller\System\Crud;

use App\Entity\Location\Region;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RegionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Region::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];
        $fields[] = TextField::new('title');
        $fields[] = TextField::new('titleEn');
        if ($pageName === Crud::PAGE_INDEX) {
            $fields[] = AssociationField::new('pilots');
        }
        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            $fields[] = TextEditorField::new('couponDescription');
            $fields[] = TextEditorField::new('couponDescriptionEn');

        }
        return $fields;
    }
}
