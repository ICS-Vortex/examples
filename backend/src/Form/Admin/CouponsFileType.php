<?php

namespace App\Form\Admin;

use App\Entity\CouponFile;
use App\Entity\Tournament;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CouponsFileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tournament', EntityType::class, [
                'class' => Tournament::class
            ])
            ->add('sourceFile', VichFileType::class, [
                'required' => true,
                "label" => 'CSV File',
                'asset_helper' => true,
            ])
            ->add('save', SubmitType::class,[
                'label' => 'Upload coupons file'
            ])
        ;
    }

    /** @param OptionsResolver $resolver */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CouponFile::class,
            'cascade_validation' => true,
        ));
    }
}
