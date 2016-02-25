<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModuleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter title'
                ]
            ])
            ->add('rating', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter rating'
                ]
            ])
            ->add('persentSuccess', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter persent'
                ]
            ])
            ->add('time', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter time'
                ]
            ])
            ->add('attempts', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter attempts'
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => 'AppBundle\Entity\Category',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'label' => 'Category',
                'property' => 'title',
                'attr' => ['class' => 'form-control'],
                'required'  => true
            ])
            ->add('module_image', FileType::class, [
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Module'
        ));
    }

    public function getName()
    {
        return 'app_bundle_module_type';
    }
}
