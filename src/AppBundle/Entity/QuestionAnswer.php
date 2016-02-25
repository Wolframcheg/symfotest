<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 10:00
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionAnswer
 *
 * @ORM\Table(name="question_answer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuestionAnswerRepository")
 */
class QuestionAnswer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="correctly", type="boolean")
     */
    private $correctly;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question", inversedBy="questionAnswers")
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Answer", inversedBy="questionAnswers")
     */
    private $answer;

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
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * @param mixed $answer
     */
    public function setAnswer(Answer $answer = null)
    {
        $this->answer = $answer;
    }

}
