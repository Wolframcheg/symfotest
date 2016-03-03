<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnswerForPassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $answers = $options['answers'];
        $idQuestion = $options['idQuestion'];
        $idPassModule = $options['idPassModule'];

        $builder
            ->add('idQuestion', HiddenType::class, [
                'data' => $idQuestion,
            ])
            ->add('idPassModule', HiddenType::class, [
                'data' => $idPassModule,
            ]);

        foreach($answers as $answer){
            $builder->add('answer_' . $answer->getId(), CheckboxType::class,[
                'label' => $answer->getTextAnswer(),
                'required' => false
            ]);
        }

        $builder->add('all_incorrect', CheckboxType::class,[
            'label'=>'Question has no correct answers',
            'required' => false
        ]);

    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'answers' => '',
            'idQuestion' => '',
            'idPassModule' => ''
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_module_user_type';
    }
}
