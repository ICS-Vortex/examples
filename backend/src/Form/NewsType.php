<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class NewsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('public',CheckboxType::class,array(
                'label'  => 'label.publish',
                'required'=>false,
                'attr' => array(
                    'class' => 'flat',
                )
            ))
            ->add('isVideoPost',CheckboxType::class,array(
                'label'  => 'label.is.videoPost',
                'required'=>false,
                'attr' => array(
                    'class' => 'flat',
                )
            ))
            ->add('title',TextType::class,array(
                'label' => 'label.title',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('titleEn',TextType::class,array(
                'label' => 'label.title.en',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('metaTitle',TextType::class,array(
                'label' => 'label.meta_title.ru',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('metaTitleEn',TextType::class,array(
                'label' => 'label.meta_title.en',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('metaH1',TextType::class,array(
                'label' => 'label.meta_h1.ru',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('metaH1En',TextType::class,array(
                'label' => 'label.meta_h1.en',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('metaKeyword',TextType::class,array(
                'label' => 'label.keyword.ru',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('metaKeywordEn',TextType::class,array(
                'label' => 'label.keyword.en',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('image', FileType::class, array(
                'data_class' => null,
            ))
            ->add('metaDescription',TextareaType::class,array(
                'label'  => 'label.short.meta_description_ru',
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('metaDescriptionEn',TextareaType::class,array(
                'label'  => 'label.short.meta_description_en',
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('description',TextareaType::class,array(
                'label'  => 'label.short.description',
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('descriptionEn',TextareaType::class,array(
                'label'  => 'label.short.description.en',
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('ru',TextareaType::class,array(
                'label'  => 'label.description',
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('en',TextareaType::class,array(
                'label'  => 'label.description.en',
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('save',SubmitType::class,array(
                'label' => 'button.save.news',
                'attr' => array(
                    'class' => 'btn btn-success btn-sm'
                )
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Main\BlogBundle\Entity\Article'
        ));
    }
}
