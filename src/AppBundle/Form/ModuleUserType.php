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
        $builder
            ->add('module', EntityType::class, [
                'class' => 'AppBundle\Entity\Module',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.title', 'ASC');
                },
                'label' => 'Choose one or several modules',
                'property' => 'title',
                'attr' => ['class' => 'form-control'],
                'multiple' => true,
                'required'  => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ModuleUser'
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_module_user_type';
    }
}
