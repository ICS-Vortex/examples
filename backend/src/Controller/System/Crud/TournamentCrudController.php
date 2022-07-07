<?php

namespace App\Controller\System\Crud;

use App\Entity\Tournament;
use App\Field\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class TournamentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tournament::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig');
    }

    public function configureFields(string $pageName): iterable
    {
        $locale = ($this->getContext()->getRequest()->getLocale());
        $fields = [];
        if ($pageName === Crud::PAGE_INDEX) {
            if ($locale === 'en') {
                $fields[] = TextField::new('titleEn');
            } else {
                $fields[] = TextField::new('title');
            }
        } else {
            $fields[] = TextField::new('title');
            $fields[] = TextField::new('titleEn');
        }
        $fields[] = BooleanField::new('provideCoupons')->setHelp('Give coupons for users in participation');
        $fields[] = BooleanField::new('hidden')->setHelp('Hide or show tournament');
        $fields[] = BooleanField::new('googleSheetExport')->setHelp('Enables or disables export to Google Sheet. Requires sheet id and tab names if enabled');
        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            $fields[] = TextField::new('googleSheetId')->setHelp('Google Sheet ID, which can be taken from URL after /d/');
            $fields[] = TextField::new('googleSheetTab')->setHelp('Google sheet TAB title');
        }
        $fields[] = AssociationField::new('aircraftsClass');
        $fields[] = AssociationField::new('servers');
        if ($pageName === Crud::PAGE_INDEX) {
            $fields[] = ImageField::new('banner')
                ->setBasePath($this->getParameter('app.path.tournaments_banners_images'));
        }
        if ($pageName === Crud::PAGE_NEW || $pageName === Crud::PAGE_EDIT) {
            $fields[] = VichImageField::new('bannerFile')->setHelp('Banner on Russian page');
            $fields[] = VichImageField::new('bannerEnFile')->setHelp('Banner on English page');
            $fields[] = TextEditorField::new('description')->setFormType(CKEditorType::class);
            $fields[] = TextEditorField::new('descriptionEn')->setFormType(CKEditorType::class);
        }


        $fields[] = DateTimeField::new('start')->setFormat('dd.MM.YYYY HH:MM')->renderAsNativeWidget();
        $fields[] = DateTimeField::new('end')->setFormat('dd.MM.YYYY HH:MM')->renderAsNativeWidget();
        if ($pageName === Crud::PAGE_EDIT) {
            $fields[] = BooleanField::new('finished');
        }
        return $fields;
    }
}
