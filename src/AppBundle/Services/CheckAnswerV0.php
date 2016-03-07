<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 04.03.16
 * Time: 15:50
 */

namespace AppBundle\Services;


use Symfony\Bridge\Doctrine\RegistryInterface;

class CheckAnwerV0
{
    private $doctrine;

    public function __construct(RegistryInterface $registryInterface)
    {
        $this->doctrine = $registryInterface;
    }

    public function checkAnswers(array $data)
    {
        $em = $this->doctrine->getManager();
        $question = $em->getRepository('AppBundle:Question')
            ->find($data['idQuestion']);

        $originalAnswers = $question->getAnswers();
        $countOriginalAnswers = count($originalAnswers);

        $sumTrueCorrect = 0;
        $sumAllCorrect = 0;
        $countAllTrueAnswers = 0;
        $countFalseAnswers = 0;
        $countAllFalseAnswers = 0;

        foreach ($originalAnswers as $item) {
            $item->getCorrectly() ? $countAllTrueAnswers++ : $countAllFalseAnswers++;
            if ($item->getCorrectly() === $data["answer_{$item->getId()}"]) {
                if ($item->getCorrectly() === true) {
                    $sumTrueCorrect++;
                }
                $sumAllCorrect++;
            } else {
                if ($item->getCorrectly() === false) {
                    $countFalseAnswers++;
                }
            }
        }
        //first type question
        if ($question->getAllIncorrect() || $data['answer_all_incorrect']) {
            if ($question->getAllIncorrect() === $data['answer_all_incorrect'] && $question->getAllIncorrect() === true && $sumTrueCorrect == 0) {

                return $result = 1;
            }

            return $result = 0;
        }

        // second type question
        if ($countAllTrueAnswers == 1) {
            if ($countFalseAnswers == 1) {
                $result = $sumTrueCorrect != 0 ? 1 / $sumTrueCorrect / 2 : 0;
            } elseif ($countFalseAnswers > 1) {
                $result = 0;
            }
            else {
                $result = $sumTrueCorrect != 0 ? 1 / $sumTrueCorrect : 0;
            }
            //third type question
        } else {
            if ($countAllTrueAnswers > 1) {
                //   $result = $sumTrueCorrect != 0 ? ((1 / $countAllTrueAnswers) * $sumTrueCorrect) - ((1 / $countAllFalseAnswers) * $countFalseAnswers) : 0;
                $result = $sumAllCorrect != 0 ? ((1 / $countOriginalAnswers) * $sumAllCorrect) : 0;
            } else {
                $result =  0;
            }
        }

        return $result;
    }
}
