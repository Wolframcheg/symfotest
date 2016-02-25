<?php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('textAnswer', TextType::class,[
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter answer'
                ]
            ])
            ->add('correctly', CheckboxType::class,[
                'required' => false
            ])
            ->add('question', HiddenType::class);
            /*->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();
                if ($data == null) {
                    $form->add('textAnswer', TextType::class, array(
                        'data' => 'Нет правильного ответа',
                        'attr' => [
                            'class' => 'form-control',
                        ]
                    ));
                }
            });*/


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Answer'
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_answer_type';
    }
}
