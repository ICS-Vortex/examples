<?php

namespace App\Form;

use App\Entity\Faq;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FaqType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('question',TextType::class,array(
                'label'=>'label.question',
                'required' => true,
            ))
            ->add('questionEn',TextType::class,array(
                'label' => 'label.questionEn',
                'required' => true,
            ))

            ->add('answer', TextareaType::class,array(
                'label'  => 'label.answer',
                'required' => true,
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('answerEn', TextareaType::class,array(
                'label'  => 'label.answerEn',
                'required' => true,
                'attr'   =>  array(
                    'class'   => 'editor',
                    'rows' => 10,
                    'cols' => 80
                )
            ))
            ->add('forSender', CheckboxType::class, [
                'required' => true,
                'invalid_message' => 'Invalid status value',
            ])
            ->add('save',SubmitType::class,array(
                'label' => 'button.save',
                'attr' => array(
                    'class' => 'btn btn-success btn-sm'
                )
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults(array(
            'data_class' => Faq::class
        ));
    }
}
