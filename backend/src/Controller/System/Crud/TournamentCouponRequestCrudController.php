<?php

namespace App\Controller\System\Crud;

use App\Entity\TournamentCouponRequest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TournamentCouponRequestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TournamentCouponRequest::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('pilot')
            ->add('tournament')
            ->add('server')
            ->add('active')
            ->add('acceptTime')
            ->add('createdAt');
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];
        $fields[] = AssociationField::new('pilot');
        if ($pageName === Crud::PAGE_INDEX) {
            $fields[] = TextField::new('pilot.region');
        }
        $fields[] = AssociationField::new('tournament');
        $fields[] = AssociationField::new('server');
        $fields[] = BooleanField::new('active')->setHelp('Becomes disabled when user press Receive Coupon');
        $fields[] = DateTimeField::new('acceptTime')->setHelp('Time when user hits Receive Coupon');
        $fields[] = DateTimeField::new('createdAt')->setHelp('Time when coupon request was created');
        $fields[] = BooleanField::new('transferred')->setHelp('Set it when coupon was added to Google Sheets API');

        return $fields;
    }
}
