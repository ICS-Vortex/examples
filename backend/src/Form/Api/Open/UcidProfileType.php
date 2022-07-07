<?php

namespace App\Form\Api\Open;

use App\Entity\Location\Region;
use App\Entity\Pilot;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UcidProfileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('email', EmailType::class, [
                'required' => true
            ])
            ->add('surname', TextType::class, [
                'required' => true
            ])
            ->add('country', TextType::class, [
                'required' => true
            ])
            ->add('language', TextType::class, [
                'required' => true
            ])
            ->add('birthday', TextType::class, [
                'required' => false
            ])
            ->add('squad', TextType::class, [
                'required' => false
            ])
            ->add('publishPhoto', CheckboxType::class, [
                'required' => false
            ])
            ->add('youtubeChannelUrl', UrlType::class, [
                'required' => false
            ])
            ->add('twitchChannelUrl', UrlType::class, [
                'required' => false
            ])
            ->add('vkProfileUrl', UrlType::class, [
                'required' => false
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'required' => true
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Pilot::class,
            'csrf_protection' => false,
        ));
    }
}
