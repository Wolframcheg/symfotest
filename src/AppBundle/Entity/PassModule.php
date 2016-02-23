<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 10:01
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

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
     */
    private $timeStart;

    /**
     * @ORM\Column(name="time_finish", type="datetime")
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

}
