<?php

namespace App\Controller\System\Crud;

use App\Entity\SocialLink;
use App\Repository\SocialLinkRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class SocialLinkCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SocialLink::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            ChoiceField::new('icon')->setChoices(SocialLinkRepository::$icons)
                ->setTemplatePath('_layout/icon_template.html.twig'),
            UrlField::new('url'),
            BooleanField::new('newWindow'),
        ];
    }
}
