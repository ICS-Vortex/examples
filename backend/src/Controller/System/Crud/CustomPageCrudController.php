<?php

namespace App\Controller\System\Crud;

use App\Entity\CustomPage;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class CustomPageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CustomPage::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];
        $fields[] = BooleanField::new('public')->renderAsSwitch(true);
        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            $fields[] = AssociationField::new('tournament')->setHelp('Helps to link page and Tournament');
        }
        $fields[] = TextField::new('titleEn');
        $fields[] = TextField::new('titleRu');
        $fields[] = TextField::new('url')->setHelp('Short URL text in URL string');
        $fields[] = IntegerField::new('position')->setHelp('Determines page order');
        $fields[] = TextEditorField::new('contentEn')->setFormType(CKEditorType::class);
        $fields[] = TextEditorField::new('contentRu')->setFormType(CKEditorType::class);

        return $fields;
    }
}
