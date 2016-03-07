<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 04.03.16
 * Time: 15:50
 */

namespace AppBundle\Services;


use Symfony\Bridge\Doctrine\RegistryInterface;

class CheckAnwers
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
        $countOriginalAnswers = $question->getCountAnswers();

        $sumAllCorrect = 0;
        $sumCorrectChecks = 0;
        $sumFalseAnswers = 0;
        $countAllTrueAnswers = 0;
        $countAllChecks = 0;

        foreach ($originalAnswers as $item) {
            $item->getCorrectly() ? $countAllTrueAnswers++ : null;
            $data["answer_{$item->getId()}"] ? $countAllChecks++ : null;
            if ($item->getCorrectly() === $data["answer_{$item->getId()}"]) {
                $sumAllCorrect++;
                if ($item->getCorrectly())
                    $sumCorrectChecks++;
            } else {
                if ($item->getCorrectly() === false) {
                    $sumFalseAnswers++;
                }
            }
        }

        if ($countAllTrueAnswers == 1) {
            $maxCountAnswers = 2;
        } else {
            $maxCountAnswers = $countAllTrueAnswers;
        }

        if ($question->getAllIncorrect() || $data['answer_all_incorrect']) {
            if ($question->getAllIncorrect() === $data['answer_all_incorrect'] && $question->getAllIncorrect() === true &&
                $sumAllCorrect == $countOriginalAnswers
            )
                return $result = 1;

            return $result = 0;
        }


        if ($sumAllCorrect && $sumCorrectChecks && $countAllChecks <= $maxCountAnswers) {
            // second type question
            if ($countAllTrueAnswers == 1) {
                $result = (1 / $maxCountAnswers) * ($sumCorrectChecks + ($sumFalseAnswers > 0 ? 0: 1));
            }
            // third type question
            elseif ($countAllTrueAnswers > 1) {
                $result = (1 / $maxCountAnswers) * $sumCorrectChecks;
            }
        } else {
            $result = 0;
        }

        return $result;
    }
}
