<?php

namespace App\Controller\System\Crud;

use App\Entity\CustomTourRequest;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CustomTourRequestController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CustomTourRequest::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextField::new('titleEn'),
            DateTimeField::new('start'),
        ];
    }
}