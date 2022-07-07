<?php

namespace App\Form;

use App\Entity\Model\FinishRegistration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FinishRegistrationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', TextType::class, ['required' => true])
            ->add('repeatPassword', TextType::class, ['required' => true])
            ->add('favouritePlane', TextType::class)
            ->add('birthday', TextType::class)
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('about', TextareaType::class)
            ->add('token', TextType::class, ['required' => true]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => FinishRegistration::class,
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ));
    }
}
