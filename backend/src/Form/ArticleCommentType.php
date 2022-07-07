<?php

namespace App\Form;

use App\Entity\ArticleComment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleCommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('author', TextType::class, [
                'label' => false,
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'required' => true,
            ])
            ->add('phone', TextType::class, [
                'label' => false,
                'required' => true,
            ])
            ->add('recaptchaToken', TextType::class, [
                'label' => false,
                'required' => true,
            ])
            ->add('comment', TextareaType::class, [
                'required' => true,
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => ArticleComment::class,
            'csrf_protection' => false,
        ]);
    }
}
