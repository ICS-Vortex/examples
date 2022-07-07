<?php

namespace App\Controller\System\Crud;

use App\Entity\Ban;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class BanCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ban::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->remove(Crud::PAGE_INDEX, Action::NEW)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_DETAIL, Action::EDIT)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('pilot'),
            TextField::new('ipAddress'),
            AssociationField::new('server'),
            TextField::new('reason'),
            DateTimeField::new('bannedFrom'),
            DateTimeField::new('bannedUntil'),
        ];
    }
}
