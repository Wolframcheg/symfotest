<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
                    'min' => 0,
                    'max' => 10,
                    'class' => 'form-control',
                    'placeholder' => 'enter sort'
                ]
            ])
            ->add('answers', CollectionType::class, [
                'entry_type' => AnswerType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event)  {
                $data = $event->getData();

                foreach ($data->getAnswers() as $item) {
                    $item->setQuestion($data);
                }

                $event->setData($data);
            });


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
