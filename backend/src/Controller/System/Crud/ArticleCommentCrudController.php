<?php

namespace App\Controller\System\Crud;

use App\Entity\ArticleComment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ArticleCommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ArticleComment::class;
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
