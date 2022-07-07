<?php

namespace App\Controller\System\Crud;

use App\Entity\Article;
use App\Field\VichImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $page): iterable
    {
        $pages = [];
        $pages[] = AssociationField::new('server');
        $pages[] = AssociationField::new('tag');
        switch ($page) {
            case Crud::PAGE_INDEX:
                $pages[] = TextField::new('title');
                $pages[] = AssociationField::new('category');
                $pages[] = ImageField::new('image')
                    ->setBasePath($this->getParameter('app.path.article_images'));
                $pages[] = BooleanField::new('isVideoPost')->renderAsSwitch(false);
                $pages[] = BooleanField::new('public');
                break;
            case Crud::PAGE_DETAIL:

                break;
            case Crud::PAGE_NEW:
            case Crud::PAGE_EDIT:
                return [
                    AssociationField::new('server'),
                    AssociationField::new('tag'),
                    BooleanField::new('public'),
                    BooleanField::new('isVideoPost'),
                    TextField::new('youtubeShortCode'),
                    TextField::new('title'),
                    TextField::new('titleEn'),
                    VichImageField::new('imageFile'),
                    TextField::new('description'),
                    TextField::new('descriptionEn'),
                    TextField::new('metaH1'),
                    TextField::new('metaH1En'),
                    TextField::new('metaTitle'),
                    TextField::new('metaTitleEn'),
                    TextField::new('metaDescription'),
                    TextField::new('metaDescriptionEn'),
                    TextField::new('metaKeyword'),
                    TextField::new('metaKeywordEn'),
                    TextEditorField::new('en'),
                    TextEditorField::new('ru'),
                ];
        }

        return $pages;
    }
}
