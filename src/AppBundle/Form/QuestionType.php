<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        $newSort = $options['new_sort'];
        $sortAttrs = [
            'attr' => [
                'class' => 'form-control',
            ],
            'choices' => [1=>1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20],
            'choices_as_values' => true,
        ];

        if($newSort)$sortAttrs['data'] = $newSort;

        $builder
            ->add('textQuestion', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'enter question'
                ]
            ])
            ->add('sort', ChoiceType::class, $sortAttrs)
            ->add('allIncorrect', CheckboxType::class, [
                'required' => false,
                'label' => 'Choose it if the question has no correct answers '
            ])
            ->add('answers', CollectionType::class, [
                'entry_type' => AnswerType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => ''
                ],

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
            'data_class' => 'AppBundle\Entity\Question',
            'new_sort' => ''
        ));
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_question_type';
    }
}
