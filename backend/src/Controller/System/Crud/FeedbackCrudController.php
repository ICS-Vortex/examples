<?php

namespace App\Controller\System\Crud;

use App\Entity\Feedback;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FeedbackCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Feedback::class;
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
