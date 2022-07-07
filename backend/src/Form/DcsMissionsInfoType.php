<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DcsMissionsInfoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array(
                'label'  => 'label.title'

            ))
            ->add('isEvent', CheckboxType::class, array(
                'label'    => 'label.isevent',
                'required' => false,
                'attr' => array(
                    'class' => 'flat'
                )
            ))
            ->add('description', TextareaType::class,array(
                'label'  => 'label.description',
                'required' => false,
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('descriptionEn', TextareaType::class,array(
                'label'  => 'label.description.en',
                'required' => false,
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('deleted',CheckboxType::class,array(
                'label' => 'label.deleted',
                'required' => false,
                'attr' => array(
                    'class' => 'flat'
                )
            ))
            ->add('save',SubmitType::class,array(
                'label' => 'button.save.mission',
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
            'data_class' => 'Dcs\CoreBundle\Entity\Missions'
        ));
    }
}
?>
