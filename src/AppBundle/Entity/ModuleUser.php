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
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

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
     *
     */
    public function __construct()
    {
        $this->passModules = new ArrayCollection();
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

    public function getCountPassModules()
    {
        return count($this->passModules);
    }

    private function getInactivePassModules(){
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('isActive', false));

        return $this->passModules->matching($criteria);
    }


}
