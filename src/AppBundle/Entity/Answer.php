<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 10:00
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Answer
 *
 * @ORM\Table(name="answer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="text_answer", type="string")
     * @Assert\NotBlank()
     */
    private $textAnswer;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\QuestionAnswer", mappedBy="answer")
     */
    private $questionAnswers;

    public function __construct()
    {
        $this->questionAnswers = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getQuestionAnswers()
    {
        return $this->questionAnswers;
    }

    public function addQuestionAnswer(QuestionAnswer $questionAnswer)
    {
        $this->questionAnswers->add($questionAnswer);

        return $this;
    }

    public function removeQuestionAnswer(QuestionAnswer $questionAnswer)
    {
        $this->questionAnswers->removeElement($questionAnswer);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTextAnswer()
    {
        return $this->textAnswer;
    }

    /**
     * @param mixed $textAnswer
     */
    public function setTextAnswer($textAnswer)
    {
        $this->textAnswer = $textAnswer;
    }


}