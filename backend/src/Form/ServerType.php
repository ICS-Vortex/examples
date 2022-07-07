<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ServerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('showBanList', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('isAerobatics', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('isModern', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('isPvp', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('sendDiscordNotifications', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('sendDiscordServerNotifications', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('sendDiscordCombatNotifications', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('sendDiscordFlightNotifications', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('sendDiscordFriendlyFireNotifications', CheckboxType::class, array(
                'required' => false,
            ))
            ->add('name', TextType::class, array(
                'required' => true,
            ))
            ->add('discordBotToken', TextType::class, array(
                'required' => false,
            ))
            ->add('discordServerId', TextType::class, array(
                'required' => false,
            ))
            ->add('discordWebHook', TextType::class, array(
                'required' => false,
            ))
            ->add('backgroundImageFile', VichImageType::class, array(
                'data_class' => null,
                'label' => 'label.background',
                'required' => false,
            ))
            ->add('email', TextType::class, array(
                'required' => true,
            ))
            ->add('address', TextType::class, array(
                'required' => false,
            ))
            ->add('port', TextType::class, array(
                'required' => false,
            ))
            ->add('srsAddress', TextType::class, array(
                'required' => false,
            ))
            ->add('teamSpeakAddress', TextType::class, array(
                'required' => false,
            ))
            ->add('reportsLocation', TextType::class, array(
                'required' => false,
            ))
            ->add('srsFile', TextType::class, array(
                'required' => false,
            ))
            ->add('description',TextareaType::class,array(
                'required' => false,
                'label'  => 'label.description',
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 300
                )
            ))
            ->add('descriptionEn',TextareaType::class,array(
                'required' => false,
                'label'  => 'label.descriptionEn',
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 300
                )
            ))

            ->add('save',SubmitType::class,array(
                'label' => 'button.save.server',
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
            'data_class' => 'App\Entity\Server',
            'allow_extra_fields' => true,
            'csrf_protection' => false,
        ));
    }
}
