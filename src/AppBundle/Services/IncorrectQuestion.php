<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 26.02.16
 * Time: 14:53
 */

namespace AppBundle\Services;


use AppBundle\Entity\Answer;
use AppBundle\Entity\Question;
use Symfony\Bridge\Doctrine\RegistryInterface;

class IncorrectQuestion
{
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function insertIncorrect(Question $question)
    {
        $em = $this->doctrine->getManager();

        $answer = new Answer();
        $answer->setQuestion($question);
        $answer->setCorrectly(true);
        $answer->setTextAnswer('There is no correct answer');

        $em->persist($answer);

        return;
    }
}