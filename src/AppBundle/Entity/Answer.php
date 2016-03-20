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
     * @ORM\Column(name="correctly", type="boolean")
     */
    private $correctly;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question", inversedBy="answers")
     */
    private $question;

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * @param mixed $question
     */
    public function setQuestion(Question $question = null)
    {
        $this->question = $question;
    }

    /**
     * @return mixed
     */

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

    /**
     * @return mixed
     */
    public function getCorrectly()
    {
        return $this->correctly;
    }

    /**
     * @param mixed $correctly
     */
    public function setCorrectly($correctly)
    {
        $this->correctly = $correctly;
    }
}