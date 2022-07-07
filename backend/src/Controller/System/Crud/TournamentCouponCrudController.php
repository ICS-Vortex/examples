<?php

namespace App\Controller\System\Crud;

use App\Entity\TournamentCoupon;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TournamentCouponCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TournamentCoupon::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('pilot'),
            AssociationField::new('tournament'),
            TextField::new('code'),
            DateTimeField::new('couponDeadline'),
        ];
    }
}
