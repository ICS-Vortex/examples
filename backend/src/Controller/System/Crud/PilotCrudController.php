<?php

namespace App\Controller\System\Crud;

use App\Entity\Pilot;
use App\Repository\BaseUserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CountryField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PilotCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pilot::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];

        $fields[] = TextField::new('username');
        $fields[] = AssociationField::new('region');
        $fields[] = TextField::new('ucid');
        $fields[] = TextField::new('ipAddress');
        $fields[] = EmailField::new('email');
        $fields[] = ChoiceField::new('roles')->setChoices(BaseUserRepository::$roles)->allowMultipleChoices();
        $fields[] = TextField::new('name');
        $fields[] = TextField::new('surname');
        $fields[] = TextField::new('phone');

        $fields[] = BooleanField::new('banned');
        $fields[] = BooleanField::new('checked')->renderAsSwitch(false);
        $fields[] = BooleanField::new('enabled');

        return  $fields;
    }
}
