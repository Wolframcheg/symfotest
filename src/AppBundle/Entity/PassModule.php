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
class PassModule
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="rating", type="integer")
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
     */
    private $currentQuestion;


    public function __construct()
    {
        $this->isActive = true;
        $this->rating = 0;
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


}
