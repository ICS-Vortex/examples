<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
class DcsRegistrationTicketsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pilot',EntityType::class,array(
                'label' => 'label.pilot',
                'class' => 'Dcs\CoreBundle\Entity\Pilots',
                'attr' => array(
                    'class' => 'select2'
                )
            ))
            ->add('deadline', DateTimeType::class,array(
                'label' => 'label.deadline',
                'widget' => 'single_text',
                'attr'=> array (
                    'data-date-format'=>'DD.MM.YYYY HH:mm'
                )
            ))
            ->add('save', \Symfony\Component\Form\Extension\Core\Type\SubmitType::class,array(
                'label' => 'button.save.registration.ticket',
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
            'data_class' => 'System\SecurityBundle\Entity\RegistrationTickets'
        ));
    }
}
