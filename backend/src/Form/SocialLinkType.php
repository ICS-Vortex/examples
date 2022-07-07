<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SocialLinkType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'label.title'
            ))
            ->add('icon', ChoiceType::class, array(
                'label' => 'label.icon',
                'choices' => array(
                    'Facebook' => 'fa-facebook-square',
                    'Flickr' => 'fa-flickr',
                    'Google' => 'fa-google',
                    'Instagram' => 'fa-instagram',
                    'LinkedIn' => 'fa-linkedin-square',
                    'Twitter' => 'fa-twitter',
                    'Vk' => 'fa-vk',
                    'Youtube' => 'fa-youtube'
                ),
                'choices_as_values' => true,
                'attr' => array(
                    'class' => 'select2'
                )
            ))
            ->add('url', TextType::class, array(
                'label' => 'label.url'
            ))
            ->add('save',SubmitType::class,array(
                'label' => 'button.link.save',
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
            'data_class' => 'Main\CoreBundle\Entity\SocialLink'
        ));
    }
}
