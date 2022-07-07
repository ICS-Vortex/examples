<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Web\SchoolBundle\Repository\PlaningTableRepository;
use Web\SchoolBundle\Repository\StudentsPlaningDaysRepository;

class PlaningTableType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, array(
                'label' => 'label.mark.type',
                'choices' => PlaningTableRepository::$types,
                'attr' => array(
                    'class' => 'select2'
            )))
            ->add('description',TextareaType::class,array(
                'required' => false,
                'label'  => 'label.mark.comment',
                'attr'   =>  array(
                    'rows' => 10,
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
            'data_class' => 'Web\SchoolBundle\Entity\PlaningTable',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ));
    }
}