<?php

namespace App\Form\Api;

use App\Entity\Pilot;
use App\Entity\Plane;
use App\Transformer\Api\DevicesTransformer;
use App\Transformer\Api\FavouritePlaneTransformer;
use App\Transformer\DateTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ApiProfileType extends AbstractType
{
    /**
     * @var FavouritePlaneTransformer
     */
    private FavouritePlaneTransformer $favouritePlaneTransformer;
    /**
     * @var DevicesTransformer
     */
    private DevicesTransformer $devicesTransformer;
    /**
     * @var DateTransformer
     */
    private DateTransformer $dateTransformer;

    public function __construct(FavouritePlaneTransformer $favouritePlaneTransformer,
                                DevicesTransformer $devicesTransformer,
                                DateTransformer $dateTransformer)
    {
        $this->favouritePlaneTransformer = $favouritePlaneTransformer;
        $this->devicesTransformer = $devicesTransformer;
        $this->dateTransformer = $dateTransformer;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('favouritePlane', TextType::class)
            ->add('devices', TextType::class)
            ->add('birthday', TextType::class)
            ->add('name', TextType::class)
            ->add('surname', TextType::class)
            ->add('about', TextareaType::class)
            ->add('twitchChannelUrl', UrlType::class)
            ->add('youtubeChannelUrl', UrlType::class);

        $builder->get('favouritePlane')->addModelTransformer($this->favouritePlaneTransformer);
        $builder->get('devices')->addModelTransformer($this->devicesTransformer);
        $builder->get('birthday')->addModelTransformer($this->dateTransformer);
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
