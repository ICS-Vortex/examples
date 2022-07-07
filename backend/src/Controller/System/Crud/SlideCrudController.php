<?php

namespace App\Controller\System\Crud;

use App\Entity\Slide;
use App\Field\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SlideCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Slide::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            NumberField::new('orderNumber'),
            TextField::new('titleEn'),
            TextField::new('descriptionEn'),
            TextField::new('url'),
            TextField::new('urlTitleEn'),
        ];

        if ($pageName === Crud::PAGE_INDEX || $pageName === Crud::PAGE_DETAIL) {
            $fields[] = ImageField::new('image')
                ->setBasePath($this->getParameter('app.path.slider_images'));
        } else {
            $fields = [];
            $fields[] = NumberField::new('orderNumber');
            $fields[] = TextField::new('title');
            $fields[] = TextField::new('titleEn');
            $fields[] = TextField::new('description');
            $fields[] = TextField::new('descriptionEn');
            $fields[] = TextField::new('url');
            $fields[] = TextField::new('urlTitle');
            $fields[] = TextField::new('urlTitleEn');
            $fields[] = VichImageField::new('imageFile');
        }

        return $fields;
    }
}
