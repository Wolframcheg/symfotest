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
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\QuestionRepository")
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="sort", type="integer")
     */
    private $sort;

    /**
     * @ORM\Column(name="text_question", type="string")
     * @Assert\NotBlank()
     */
    private $textQuestion;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Module", inversedBy="questions")
     */
    private $module;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\QuestionAnswer", mappedBy="question")
     */
    private $questionAnswers;

    /**
     *
     */
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

    /**
     * @param QuestionAnswer $questionAnswer
     * @return $this
     */
    public function addQuestionAnswer(QuestionAnswer $questionAnswer)
    {
        $this->questionAnswers->add($questionAnswer);

        return $this;
    }

    /**
     * @param QuestionAnswer $questionAnswer
     */
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
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param mixed $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return mixed
     */
    public function getTextQuestion()
    {
        return $this->textQuestion;
    }

    /**
     * @param mixed $textQuestion
     */
    public function setTextQuestion($textQuestion)
    {
        $this->textQuestion = $textQuestion;
    }

    /**
     * @return mixed
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param mixed $module
     */
    public function setModule(ModuleUser $module = null)
    {
        $this->module = $module;
    }



}
