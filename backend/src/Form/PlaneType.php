<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PlaneType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description',TextareaType::class,array(
                'required' => false,
                'label'  => 'label.description',
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 200
                )
            ))
            ->add('image', FileType::class, [
                'label'  => 'label.image',
                'data_class' => null,
                'required' => false,
                'attr' => [
                    'placeholder' => '400x400'
                ]
            ])
            ->add('redImage', FileType::class, [
                'label'  => 'label.redteam',
                'data_class' => null,
                'required' => false,
                'attr' => [
                    'placeholder' => '400x400'
                ]
            ])
            ->add('blueImage', FileType::class, [
                'label'  => 'label.blueteam',
                'data_class' => null,
                'required' => false,
                'attr' => [
                    'placeholder' => '400x400'
                ]
            ])


            ->add('save',SubmitType::class, [
                'label' => 'button.save',
                'attr' => [
                    'class' => 'btn btn-info btn-sm'
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'App\Entity\Plane',
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ));
    }
}
