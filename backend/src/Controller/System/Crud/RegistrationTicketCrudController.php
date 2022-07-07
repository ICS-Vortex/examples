<?php

namespace App\Controller\System\Crud;

use App\Entity\RegistrationTicket;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RegistrationTicketCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RegistrationTicket::class;
    }

    public function configureFields(string $page): iterable
    {
        return [
            AssociationField::new('pilot')->autocomplete(),
            TextField::new('email'),
            TextField::new('token'),
            DateTimeField::new('deadline'),
            BooleanField::new('accepted')->renderAsSwitch(false),
            BooleanField::new('issued')->renderAsSwitch(false),
        ];
    }
}
