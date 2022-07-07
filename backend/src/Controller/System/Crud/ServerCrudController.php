<?php

namespace App\Controller\System\Crud;

use App\Entity\Server;
use App\Field\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ServerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Server::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];
        if ($pageName === Crud::PAGE_INDEX) {
            $fields[] = IdField::new('id');
        }
        $fields[] = TextField::new('name');
        if ($pageName === Crud::PAGE_INDEX) {
            $fields[] = BooleanField::new('isOnline')->renderAsSwitch(false);
        }
        $fields[] = TextField::new('version');
        $fields[] = TextField::new('email');
        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            $fields = array_merge($fields, [
                TextField::new('address'),
                TextField::new('port'),

                TextField::new('teamSpeakAddress'),
                TextField::new('srsAddress'),
                TextField::new('discordAddress'),
                TextField::new('mumbleAddress'),

                TextField::new('reportsLocation'),
                TextEditorField::new('description'),
                TextEditorField::new('descriptionEn'),
                NumberField::new('orderPosition'),
            ]);
        }

        if ($pageName === Crud::PAGE_DETAIL) {
            $fields = array_merge($fields, [
                TextField::new('discordServerId'),
                TextField::new('discordBotToken'),
                BooleanField::new('sendDiscordNotifications'),
                BooleanField::new('sendDiscordServerNotifications'),
                BooleanField::new('sendDiscordFlightNotifications'),
                BooleanField::new('sendDiscordCombatNotifications'),
                BooleanField::new('showMap'),
                TextField::new('discordWebHook'),
                TextField::new('folder'),
                TextField::new('teamSpeakAddress'),
                TextField::new('srsAddress'),
                TextField::new('srsFile'),
            ]);
        }

        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL) {
            $fields[] = TextField::new('identifier');
        } else {
            $fields[] = VichImageField::new('backgroundImageFile');
        }
        $fields[] = BooleanField::new('active');
        $fields[] = BooleanField::new('showMap');
        $fields[] = BooleanField::new('isAerobatics');
        $fields[] = BooleanField::new('isModern');
        $fields[] = BooleanField::new('isPvp');
        $fields[] = BooleanField::new('showBanList');
        $fields[] = BooleanField::new('beta');
        $fields[] = BooleanField::new('sendVersionUpdateEmails');
        return $fields;
    }
}
