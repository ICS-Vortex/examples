<?php

namespace App\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ManualSubCategoryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,array(
                'label' => 'label.title',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('titleEn',TextType::class,array(
                'label' => 'label.title.en',
                'attr' => array(
                    'class' => 'form-control'
                ))
            )
            ->add('category',EntityType::class,array(
                'placeholder' => 'placeholder.manuals.category',
                'label'=>'label.docs.category',
                'required' => true,
                'class' => 'Web\SchoolBundle\Entity\ManualsCategories',
                'attr' => array(
                    'class' => 'select2'
                ),
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('category')
                        ->where('category.active = :active')
                        ->setParameter('active', true)
                        ;
                },
            ))
            ->add('save', SubmitType::class,array(
                'label' => 'button.save.manual',
                'attr' => array(
                    'class' => 'btn btn-success btn-sm'
                )
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Web\SchoolBundle\Entity\ManualSubCategory'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'web_schoolbundle_manualsubcategory';
    }


}
