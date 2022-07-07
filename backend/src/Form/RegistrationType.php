<?php

namespace App\Form;

use App\Entity\GameDevice;
use App\Entity\Model\RegistrationModel;
use App\Entity\Pilot;
use App\Entity\Plane;
use App\Repository\GameDeviceRepository;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Form\Type\TextEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'label' => 'label.callsign',
                'attr' => [
                    'class' => 'form-control-lg'
                ]
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'label.email'
            ])
            ->add('birthday', DateType::class, [
                'required' => false,
                'placeholder' => 'Select a value',
                'label' => 'label.birthday'
            ])
            ->add('about', TextEditorType::class, [
                'required' => false,
                'label' => 'label.introduction'
            ])
            ->add('favouritePlane', EntityType::class, [
                'required' => false,
                'label' => 'label.favouritePlane',
                'class' => Plane::class
            ])
            ->add('devices', EntityType::class, [
                'required' => false,
                'label' => 'label.devices',
                'class' => GameDevice::class,
                'choice_label' => 'name',
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.id', 'ASC');
                },
            ])
            ->add('youtubeChannelUrl', UrlType::class, [
                'required' => false,
                'label' => 'label.youtubeChannelUrl'
            ])
            ->add('twitchChannelUrl', UrlType::class, [
                'required' => false,
                'label' => 'label.twitchChannelUrl'
            ])
            ->add('acceptedRules', CheckboxType::class, [
                'required' => false,
                'label' => 'label.accept_policy'
            ])
            ->add('save',SubmitType::class,array(
                'label' => 'button.register',
                'attr' => [
                    'class' => 'btn btn-info btn-danger btn-lg'
                ]
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pilot::class,
            'csrf_protection' => true,
        ]);
    }
}
