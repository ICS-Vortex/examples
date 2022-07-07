<?php

namespace App\Form;

use App\Entity\Admin;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AdminType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',TextType::class,array(
                'label'  => 'label.email'
            ))
            ->add('name',TextType::class,array(
                'label'  => 'label.name'
            ))

            ->add('surname',TextType::class,array(
                'label'  => 'label.surname',
            ))
            ->add('phone',TextType::class,array(
                'label' => 'label.phone',
                'required' => false,
                'attr' => array(
                    'data-inputmask' => "\"mask\": \"+380(##)###-##-##\"",
                    'data-mask' => null,
                    'class' => 'pilot_phone'
                )
            ))
            ->add('address',TextType::class,array(
                'label'  => 'label.address',
                'required' => false,
            ))
            ->add('avatar', FileType::class, array(
                'label' => 'label.avatar',
                'data_class' => null,
                'required' => false,
            ))
            ->add('password', RepeatedType::class, array(
                'label' => 'label.password',
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => false,
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
            ))
            ->add('save',SubmitType::class,array(
                'label' => 'button.save.admin',
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
            'data_class' => Admin::class
        ));
    }
}
