<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('textAnswer', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter answer',
                    'style' => 'width:400px;'
                ]
            ])
            ->add('correctly', CheckboxType::class,[
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Answer',
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_answer_type';
    }
}
