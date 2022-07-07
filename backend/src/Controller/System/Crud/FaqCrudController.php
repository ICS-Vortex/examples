<?php

namespace App\Controller\System\Crud;

use App\Entity\Faq;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FaqCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Faq::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            BooleanField::new('forSender')->setHelp('Not used anymore'),
            AssociationField::new('server'),
            AssociationField::new('tournament')->setHelp('Leave server empty - if you want to link FAQ to tournament'),
            TextField::new('question'),
            TextField::new('questionEn'),
            TextEditorField::new('answer'),
            TextEditorField::new('answerEn'),
        ];
    }
}
