<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 10:00
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * ModuleUser
 *
 * @ORM\Table(name="module_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModuleUserRepository")
 */
class ModuleUser
{
    const STATUS_ACTIVE = 'active';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PassModule", mappedBy="moduleUser", cascade={"remove"})
     */
    private $passModules;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="modulesUser")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Module", inversedBy="modulesUser")
     */
    private $module;

    /**
     * @ORM\Column(type="float")
     */
    private $rating;


    public function __construct()
    {
        $this->passModules = new ArrayCollection();
        $this->status = self::STATUS_ACTIVE;
        $this->rating = 0;
    }

    /**
     * @return mixed
     */
    public function getPassModules()
    {
        return $this->passModules;
    }

    /**
     * @param PassModule $passModule
     * @return $this
     */
    public function addPassModule(PassModule $passModule)
    {
        $this->passModules->add($passModule);

        return $this;
    }

    /**
     * @param PassModule $passModule
     */
    public function removePassModule(PassModule $passModule)
    {
        $this->passModules->removeElement($passModule);
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
    public function getAttempts()
    {
      //  return $this->attempts;
        return count($this->getInactivePassModules());
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return ($this->status == self::STATUS_ACTIVE);
    }


    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return $this
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
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
     * @return $this
     */
    public function setModule(Module $module = null)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param $rating
     * @return $this
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
        return $this;
    }


    public function getCountPassModules()
    {
        return count($this->passModules);
    }

    public function getInactivePassModules()
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('isActive', false));

        return $this->passModules->matching($criteria);
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }


}
