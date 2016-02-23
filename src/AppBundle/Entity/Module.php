<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 9:16
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Entity\Category;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Module
 *
 * @ORM\Table(name="module")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModuleRepository")
 */
class Module
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string")
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(name="rating", type="integer")
     *
     */
    private $rating;

    /**
     * @ORM\Column(name="persent_success", type="integer")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     * @Assert\LessThan(value = 100)
     */
    private $persentSuccess;

    /**
     * @ORM\Column(name="time", type="integer")
     * @Assert\NotBlank()
     */
    private $time;

    /**
     * @ORM\Column(name="attempts", type="integer")
     * @Assert\NotBlank()
     */
    private $attempts;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var string
     * @Assert\File(
     *              maxSize = "3M",
     *              mimeTypes = {"image/*"},
     *              maxSizeMessage = "The file is too large ({{ size }}).Allowed maximum size is {{ limit }}",
     *              mimeTypesMessage = "The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}"
     *              )
     */
    private $moduleImage;

    /**
     * @var string
     *
     * @ORM\Column(name="nameImage", type="string", length=255, nullable=true)
     */
    private $nameImage;

    /**
     * @var string
     *
     * @ORM\Column(name="pathImage", type="string", length=255, nullable=true)
     */
    private $pathImage;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="modules")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Question", mappedBy="module")
     */
    private $questions;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ModuleUser", mappedBy="module")
     */
    private $modulesUser;

    /**
     *
     */
    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->modulesUser = new ArrayCollection();
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
    public function getPersentSuccess()
    {
        return $this->persentSuccess;
    }

    /**
     * @param mixed $persentSuccess
     */
    public function setPersentSuccess($persentSuccess)
    {
        $this->persentSuccess = $persentSuccess;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return mixed
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * @param mixed $attempts
     */
    public function setAttempts($attempts)
    {
        $this->attempts = $attempts;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory(Category $category = null)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * @param Question $question
     * @return $this
     */
    public function addQuestion(Question $question)
    {
        $this->questions->add($question);

        return $this;
    }

    /**
     * @param Question $question
     */
    public function removeQuestion(Question $question)
    {
        $this->questions->removeElement($question);
    }

    /**
     * @return mixed
     */
    public function getModulesUser()
    {
        return $this->modulesUser;
    }

    /**
     * @param ModuleUser $moduleUser
     * @return $this
     */
    public function addModuleUser(ModuleUser $moduleUser)
    {
        $this->modulesUser->add($moduleUser);

        return $this;
    }

    /**
     * @param ModuleUser $moduleUser
     */
    public function removeModuleUser(ModuleUser $moduleUser)
    {
        $this->modulesUser->removeElement($moduleUser);
    }

    /**
     * @return string
     */
    public function getModuleImage()
    {
        return $this->moduleImage;
    }


    /**
     * @param UploadedFile $uploadedFile
     */
    public function setModuleImage(UploadedFile $uploadedFile)
    {
        $this->moduleImage = $uploadedFile;
    }

    /**
     * @return string
     */
    public function getNameImage()
    {
        return $this->nameImage;
    }

    /**
     * @param string $nameImage
     */
    public function setNameImage($nameImage)
    {
        $this->nameImage = $nameImage;
    }

    /**
     * @return string
     */
    public function getPathImage()
    {
        return $this->pathImage;
    }

    /**
     * @param string $pathImage
     */
    public function setPathImage($pathImage)
    {
        $this->pathImage = $pathImage;
    }
}