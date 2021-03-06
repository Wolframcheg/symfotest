<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 10:01
 */

namespace AppBundle\Entity;


use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PassModule
 *
 * @ORM\Table(name="pass_module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PassModuleRepository")
 */
class PassModule implements \JsonSerializable
{
    const STATE_PASSED = 'passed';
    const STATE_FAILED = 'failed';
    const STATE_EXPIRED = 'expired';
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="rating", type="float")
     */
    private $rating;

    /**
     * @ORM\Column(name="time_start", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $timeStart;

    /**
     * @ORM\Column(name="time_finish", type="datetime", nullable=true)
     */
    private $timeFinish;

    /**
     * @ORM\Column(name="time_period", type="integer")
     */
    private $timePeriod;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ModuleUser", inversedBy="passModules")
     */
    private $moduleUser;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Question", inversedBy="passModules")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $currentQuestion;

    /**
     * @ORM\Column(type="array")
     */
    private $answeredQuestionIds;

    public function jsonSerialize()
    {
        return [
            'result' => $this->getAbsoluteResult(),
            'percent' => $this->getPercentResult(),
            'rating' => $this->getRating(),
            'timeStart' => $this->getTimeStart(),
            'timeFinish' => $this->getTimeFinish()
        ];
    }


    public function __construct()
    {
        $this->isActive = true;
        $this->rating = 0;
        $this->answeredQuestionIds = [];
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
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @param mixed $rating
     */
    public function addRating($rating)
    {
        $this->rating += $rating;
    }

    /**
     * @return mixed
     */
    public function getTimeStart()
    {
        return $this->timeStart;
    }

    /**
     * @param mixed $timeStart
     */
    public function setTimeStart($timeStart)
    {
        $this->timeStart = $timeStart;
    }

    /**
     * @return mixed
     */
    public function getTimeFinish()
    {
        return $this->timeFinish;
    }

    /**
     * @param mixed $timeFinish
     */
    public function setTimeFinish($timeFinish)
    {
        $this->timeFinish = $timeFinish;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getTimePeriod()
    {
        return $this->timePeriod;
    }

    /**
     * @param mixed $timePeriod
     */
    public function setTimePeriod($timePeriod)
    {
        $this->timePeriod = $timePeriod;
    }

    /**
     * @return mixed
     */
    public function getModuleUser()
    {
        return $this->moduleUser;
    }

    /**
     * @param mixed $moduleUser
     */
    public function setModuleUser(ModuleUser $moduleUser = null)
    {
        $this->moduleUser = $moduleUser;
    }

    /**
     * @return mixed
     */
    public function getCurrentQuestion()
    {
        return $this->currentQuestion;
    }

    /**
     * @param mixed $question
     */
    public function setCurrentQuestion(Question $question = null)
    {
        $this->currentQuestion = $question;
    }

    /**
     * @return mixed
     */
    public function getAnsweredQuestionIds()
    {
        return $this->answeredQuestionIds;
    }

    /**
     * @param $questionId
     * @return $this
     * @internal param Question $question
     */
    public function addAnsweredQuestionId($questionId)
    {
        $this->answeredQuestionIds[] = $questionId;

        return $this;
    }

    public function getCountPassedQuestions()
    {
        return count($this->answeredQuestionIds);
    }

    public function getAbsoluteResult()
    {
        $countQuestions = $this->getModuleUser()->getModule()->getCountQuestions();
        $maxResult = $this->getModuleUser()->getModule()->getRating();
        return $this->rating * $maxResult / $countQuestions ;
    }

    public function getPercentResult()
    {
        $countQuestions = $this->getModuleUser()->getModule()->getCountQuestions();
        return $this->rating * 100 / $countQuestions ;
    }

    public function getStateResult()
    {
        if($this->timeFinish === null)
                return self::STATE_EXPIRED;

        return $this->getPercentResult() >= $this->getModuleUser()->getModule()->getPersentSuccess() ?
                self::STATE_PASSED : self::STATE_FAILED;
    }

}
