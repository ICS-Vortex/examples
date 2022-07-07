<?php

namespace App\Controller\System\Crud;

use App\Entity\Manual;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ManualCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Manual::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $image = ImageField::new('image')
            ->setBasePath($this->getParameter('app.path.manuals_images'));
        $imageFile = ImageField::new('imageFile')->setFormType(VichImageType::class);
        $fields = [
            TextField::new('title'),
            TextField::new('titleEn'),
            BooleanField::new('public'),
            AssociationField::new('plane')->autocomplete(),
        ];

        switch ($pageName) {
            case Crud::PAGE_INDEX :
            case Crud::PAGE_DETAIL:
            $fields[] = $image;
            $fields[] = UrlField::new('document')->setTemplatePath('_layout/manual_template.html.twig');
                break;
            default:
                $fields[] = $imageFile;
                $fields[] = ImageField::new('documentFile');
                $fields[] = TextEditorField::new('description');
                $fields[] = TextEditorField::new('descriptionEn');
        }

        return $fields;
    }
}
