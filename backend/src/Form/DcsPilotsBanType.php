<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class DcsPilotsBanType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dcsPilots',EntityType::class,array(
                'label' => 'label.pilot',
                'class' => 'Dcs\CoreBundle\Entity\Pilots',
                'attr' => array(
                    'class' => 'select2'
                )
            ))
            ->add('start',DateTimeType::class,array(
                'label'=>'label.start.date',
                'widget' => 'single_text',
                'attr'=> array (
                    'data-date-format'=>'DD.MM.YYYY HH:mm'
                )
            ))
            ->add('reason',TextType::class,array(
                'label' => 'label.reason'
            ))
            ->add('reasonEn',TextType::class,array(
                'label' => 'label.reason.en'
            ))
            ->add('type', ChoiceType::class, array(
                'label' => 'ban.type',
                'choices' => array(
                    'Day' => 'DAY',
                    'Week' => 'WEEK',
                    'Month' => 'MONTH',
                    'Year' => 'YEAR',
                    'Forever' => 'FOREVER'
                ),
                'choices_as_values' => true
            ))
            ->add('save',SubmitType::class,array(
                'label' => 'button.ban.pilot',
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
            'data_class' => 'Dcs\CoreBundle\Entity\Bans'
        ));
    }
}
