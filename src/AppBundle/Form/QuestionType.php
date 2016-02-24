<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('textQuestion', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter question'
                ]
            ])
            ->add('sort', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter sort'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Question'
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_question_type';
    }
}
