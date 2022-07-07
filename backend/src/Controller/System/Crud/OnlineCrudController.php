<?php

namespace App\Controller\System\Crud;

use App\Entity\Airfield;
use App\Entity\Online;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OnlineCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Online::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->disable(Action::NEW, Action::DELETE, Action::EDIT);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('pilot'),
            AssociationField::new('server'),
            AssociationField::new('plane'),
            TextField::new('side'),
        ];
    }
}
