<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MailType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('toUser',EntityType::class,array(
                'label' => 'label.mail.to',
                'class' => 'Dcs\CoreBundle\Entity\Pilots',
                'attr' => array(
                    'class' => 'select2'
                )
            ))
            ->add('subject',TextType::class,array(
                'label'  => 'label.mail.subject',
                'required' => false,
            ))
            ->add('message', TextareaType::class,array(
                'label'  => 'label.mail.description',
                'required' => false,
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('send',SubmitType::class,array(
                'label' => 'button.send.message',
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
            'data_class' => 'System\SecurityBundle\Entity\Mail'
        ));
    }
}
