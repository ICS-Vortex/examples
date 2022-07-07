<?php


namespace App\Form;

use App\Constant\Parameter;
use App\Entity\Admin;
use App\Entity\Server;
use App\Repository\BaseUserRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServerAccessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('admins', EntityType::class, [
                'class'        => Admin::class,
                'choice_label' => 'username',
                'label'        => 'label.admins',
                'expanded'     => true,
                'multiple'     => true,
                'query_builder' => function (EntityRepository $repo) {
                    return $repo->createQueryBuilder('a')
//                        ->where('a.deleted > :deleted')
//                        ->andWhere('a.role = :role')
//                        ->setParameter('deleted', false)
//                        ->setParameter('role', BaseUserRepository::ROLE_ADMIN)
                    ;
                },
            ])
            ->add('save',SubmitType::class,array(
                'label' => 'button.save',
                'attr' => array(
                    'class' => Parameter::BUTTON_SAVE
                )
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Server::class,
        ]);
    }
}