<?php

namespace App\Controller\System\Crud;

use App\Entity\ArticleCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ArticleCategory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('titleEn'),
            AssociationField::new('articles')->autocomplete(),
            BooleanField::new('active'),
        ];
    }
}
