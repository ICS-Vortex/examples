<?php

namespace App\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
class ManualsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('public',CheckboxType::class,array(
                'label' => 'label.docs.publish',
                'required' => false,
                'attr' => array(
                    'class' => 'flat'
                )
            ))
            ->add('title',TextType::class, array(
                'label'  => 'label.docs.title',
                'required' => true,
            ))
            ->add('titleEn',TextType::class, array(
                'label'  => 'label.docs.title.en',
                'required' => true,
            ))
            ->add('keywords',TextType::class, array(
                'label'  => 'label.docs.keywords',
                'required' => true,
            ))
            ->add('keywordsEn',TextType::class, array(
                'label'  => 'label.docs.keywordsEn',
                'required' => true,
            ))
            ->add('image', FileType::class, array(
                'data_class' => null,
                'required' => false,
                'label' => 'label.picture'
            ))
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
            ->add('subCategory',EntityType::class,array(
                'placeholder' => 'placeholder.manuals.sub_category',
                'label'=>'label.docs.sub_category',
                'required' => true,
                'class' => 'Web\SchoolBundle\Entity\ManualSubCategory',
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
            ->add('course',EntityType::class,array(
                'label'=>'label.docs.course',
                'placeholder' => 'placeholder.manuals.course',
                'required' => false,
                'class' => 'Web\SchoolBundle\Entity\SchoolCourses',
                'attr' => array(
                    'class' => 'select2'
                )
            ))
            ->add('description',TextType::class, array(
                'label'  => 'label.docs.description',
                'required' => true,
            ))
            ->add('descriptionEn',TextType::class, array(
                'label'  => 'label.docs.descriptionEn',
                'required' => true,
            ))
            ->add('ru', TextareaType::class,array(
                'required' => true,
                'label'  => 'label.docs.description',
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('en', TextareaType::class,array(
                'required' => true,
                'label'  => 'label.docs.description.en',
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
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
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Web\SchoolBundle\Entity\Manuals'
        ));
    }
}
