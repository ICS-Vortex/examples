<?php

namespace App\Controller\System\Crud;

use App\Entity\Log;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class LogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Log::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('event'),
            BooleanField::new('success')->renderAsSwitch(false),
            DateTimeField::new('eventTime'),
            AssociationField::new('server'),
            TextField::new('initiatorNickname'),
            TextField::new('initiatorSide'),
            TextField::new('initiatorType'),
            TextField::new('targetNickname'),
            TextField::new('targetSide'),
            TextField::new('targetType'),
            ArrayField::new('messagesStack'),

        ];
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('event')
            ->add('success')
            ->add('server')
            ->add('initiatorNickname')
            ->add('targetNickname')
            ->add('messagesStack')
        ;
    }

}
