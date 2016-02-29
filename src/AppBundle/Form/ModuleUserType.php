<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder
            ->add('module', EntityType::class, [
                'class' => 'AppBundle\Entity\Module',
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('m')
                  /*      ->leftJoin('m.modulesUser', 'mu')
                        ->andWhere('mu.module IS NULL')
                        ->orWhere('mu.user <> :users')
                        ->setParameter('users', $user)*/
                        ->orderBy('m.title', 'ASC');
                },
                'label' => 'Choose module',
                'property' => 'title',
                'attr' => ['class' => 'chosen-select'],
                'required'  => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ModuleUser',
            'user' => ''
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_module_user_type';
    }
}
