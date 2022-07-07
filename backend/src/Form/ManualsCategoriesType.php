<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ManualsCategoriesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('active',CheckboxType::class,array(
                'label' => 'label.category.status.active',
                'required' => false,
                'attr' => array(
                    'class' => 'flat'
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
                ))
            )
            ->add('save',SubmitType::class,array(
                'label' => 'button.save.manual.category',
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
            'data_class' => 'Web\SchoolBundle\Entity\ManualsCategories'
        ));
    }
}
