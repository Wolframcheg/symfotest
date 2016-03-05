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

        foreach ($originalAnswers as $item) {
            if ($item->getCorrectly() === $data["answer_{$item->getId()}"]) {
                $sumAllCorrect++;
                if ($item->getCorrectly())
                    $sumCorrectChecks++;
            }
        }
        //first type question
        if ($question->getAllIncorrect() === true && $question->getAllIncorrect() === $data['answer_all_incorrect'] &&
            $sumAllCorrect == $countOriginalAnswers)
            return 1;

        // second type question
        if ($sumAllCorrect && $sumCorrectChecks) {
            $result = (1 / $countOriginalAnswers) * $sumAllCorrect;
        } else {
            $result = 0;
        }

        return $result;
    }
}
