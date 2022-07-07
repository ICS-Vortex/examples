<?php

namespace App\Controller\System\Crud;

use App\Entity\Faq;
use App\Entity\FeaturedVideo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class FeaturedVideoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FeaturedVideo::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('orderNumber'),
            AssociationField::new('server'),
            TextField::new('code', 'YouTube code'),
            UrlField::new('url'),
        ];
    }
}
