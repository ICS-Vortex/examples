<?php

namespace App\Form;

use App\Entity\Server;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SenderServerType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false,
                'invalid_message' => 'Incorrect server name',
            ])
            ->add('email', TextType::class, [
                'required' => true,
                'invalid_message' => 'Incorrect email address',
            ])
            ->add('address', TextType::class, [
                'required' => true,
                'invalid_message' => 'Incorrect server address',
            ])
            ->add('discordWebHook', TextType::class, [
                'required' => false,
                'invalid_message' => 'Incorrect discord web hook url',
            ])
            ->add('port', TextType::class, [
                'required' => true,
                'invalid_message' => 'Invalid port value',
            ])
            ->add('teamSpeakAddress', TextType::class, [
                'required' => false,
                'invalid_message' => 'Invalid TS address',
            ])
            ->add('srsAddress', TextType::class, [
                'required' => false,
                'invalid_message' => 'Invalid SRS address',
            ])
            ->add('srsFile', TextType::class, [
                'required' => false,
                'invalid_message' => 'Invalid SRS file path value',
            ])
            ->add('reportsLocation', TextType::class, [
                'required' => true,
                'invalid_message' => 'Invalid reports location value',
            ])
            ->add('active', CheckboxType::class, [
                'required' => true,
                'invalid_message' => 'Invalid status value',
            ])
            ->add('showMap', CheckboxType::class, [
                'required' => true,
                'invalid_message' => 'Invalid show-map value',
            ])
            ->add('sendDiscordNotifications', CheckboxType::class, [
                'required' => true,
                'invalid_message' => 'Invalid discord notifications value',
            ])
            ->add('sendDiscordServerNotifications', CheckboxType::class, [
                'required' => false,
                'invalid_message' => 'Invalid discord server notifications value',
            ])
            ->add('sendDiscordFlightNotifications', CheckboxType::class, [
                'required' => false,
                'invalid_message' => 'Invalid discord flight notifications value',
            ])
            ->add('sendDiscordCombatNotifications', CheckboxType::class, [
                'required' => false,
                'invalid_message' => 'Invalid discord combat notifications value',
            ])
            ->add('discordServerId', TextType::class, [
                'required' => false,
                'invalid_message' => 'Invalid discord server ID',
            ])
            ->add('discordBotToken', TextType::class, [
                'required' => false,
                'invalid_message' => 'Invalid discord bot token',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults(array(
            'data_class' => Server::class,
            'csrf_protection' => false,
        ));
    }
}
