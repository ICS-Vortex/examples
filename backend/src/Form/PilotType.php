<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
class PilotType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username',TextType::class, array(
                'label'  => 'label.callsign'
            ))
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
                'label'  => 'label.avatar',
                'data_class' => null,
                'required' => false,
            ))
            ->add('role', ChoiceType::class, array(
                    'label'  => 'label.role',
                    'choices' => array(
                        'User' => 'ROLE_USER',
                        'Student' => 'ROLE_STUDENT',
                        'Instructor' => 'ROLE_INSTRUCTOR',
                        'Team' => 'ROLE_TEAM',
                        'Operator' => 'ROLE_OPERATOR',
                        'Admin' => 'ROLE_ADMIN',
                        'ROOT' => 'ROLE_ROOT',
                    ),
                    'attr' => array(
                        'class' => 'select2'
                    )
                )
            )
            ->add('checked', CheckboxType::class, array(
                'label'    => 'label.status.checked',
                'required' => false,
                'attr' => array(
                    'class' => 'flat'
                )
            ))
            ->add('deleted',CheckboxType::class,array(
                'label' => 'label.status.deleted',
                'required' => false,
                'attr' => array(
                    'class' => 'flat'
                )
            ))
            ->add('save',SubmitType::class,array(
                'label' => 'button.save.pilot',
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
            'data_class' => 'App\Entity\Pilot',
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ));
    }
}
