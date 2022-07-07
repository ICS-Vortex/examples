<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
class EventsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,array(
                'label'=>'label.title'
            ))
            ->add('start', DateTimeType::class,array(
                'label'=>'label.start.time',
                'widget' => 'single_text',
                'attr'=> array (
                    'data-date-format'=>'DD.MM.YYYY HH:mm'
                )))
            ->add('end', DateTimeType::class,array(
                'label'=>'label.end.time',
                'widget' => 'single_text',
                'attr'=> array (
                    'data-date-format'=>'DD.MM.YYYY HH:mm'
                )))
            ->add('url',TextType::class,array(
                'label' => 'label.url',
                'required' => false,
            ))
            ->add('allDay',CheckboxType::class,array(
                'label'=>'label.status.allday',
                'required' => false,
                'attr' => array(
                    'class' => 'flat '
                )
            ))
            ->add('backgroundColor',TextType::class,array(
                'label'=>'label.background.color',
                'attr' => array(
                    'class' => 'my-colorpicker1 colorpicker-element'
                )
            ))
            ->add('borderColor',TextType::class,array(
                'label'=>'label.border.color',
                'attr' => array(
                    'class' => 'my-colorpicker1 colorpicker-element'
                )
            ))
            ->add('allowRegistration',CheckboxType::class,array(
                'label'=>'label.allow.registration',
                'required' => false,
                'attr' => array(
                    'class' => 'flat '
                )
            ))
            ->add('description', TextareaType::class,array(
                'label'  => 'label.description.ru',
                'required' => true,
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('descriptionEn', TextareaType::class,array(
                'label'  => 'label.description.en',
                'required' => true,
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('save',SubmitType::class,array(
                'label' => 'button.save.event',
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
            'data_class' => 'Main\CoreBundle\Entity\DcsCalendar'
        ));
    }
}
