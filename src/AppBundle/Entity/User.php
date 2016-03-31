<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 */
class User implements AdvancedUserInterface, \JsonSerializable
{
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Length(min="1", minMessage="This field can not be less than 1 character")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Length(min="1", minMessage="This field can not be less than 1 character")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=64, nullable=true)
     */
    private $tmpPassword;

    /**
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $role;

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var string
     *
     * @ORM\Column(name="fb_token", type="string", nullable=true)
     */
    protected $facebookToken;
    /**
     * @var string
     *
     * @ORM\Column(name="fb_id", type="string", nullable=true)
     */
    protected $facebookId;
    /**
     * @var string
     *
     * @ORM\Column(name="g_token", type="string", nullable=true)
     */
    protected $googleToken;
    /**
     * @var string
     *
     * @ORM\Column(name="g_id", type="string", nullable=true)
     */
    protected $googleId;

    /**
     * @ORM\Column(name="is_locked", type="boolean")
     */
    protected $isLocked;

    /**
     * @ORM\Column(name="hash", type="string", nullable=true)
     */
    protected $hash;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ModuleUser", mappedBy="user", cascade={"remove"})
     */
    private $modulesUser;

    /**
     * @ORM\Column(name="chosen_module", type="array")
     */
    private $chosenModule;

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'firstName' => $this->getFirstName(),
            'lastName' => $this->getLastName(),
            'email' => $this->getEmail()
        ];
    }

    public function __construct()
    {
        $this->isActive = false;
        $this->isLocked = false;
        $this->modulesUser = new ArrayCollection();
        $this->role = self::ROLE_USER;
        $this->chosenModule = [];
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param $role
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles()
    {
        return [$this->role];

    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param $password
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     */
    public function getSalt()
    {
        // See "Do you need to use a Salt?" at http://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one

        return;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked()
    {
        return $this->isLocked ? false : true ;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param $active
     * @return mixed
     */
    public function setIsActive($active)
    {
        return $this->isActive = $active;
    }

    /**
     * @return bool
     */
    public function getIsLocked()
    {
        return $this->isLocked;
    }

    /**
     * @param $isLocked
     * @return mixed
     */
    public function setIsLocked($isLocked)
    {
        return $this->isLocked = $isLocked;
    }

    /**
     * @return string
     */
    public function getFacebookToken()
    {
        return $this->facebookToken;
    }

    /**
     * @param string $facebookToken
     * @return $this
     */
    public function setFacebookToken($facebookToken)
    {
        $this->facebookToken = $facebookToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * @param string $facebookId
     * @return $this
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleToken()
    {
        return $this->googleToken;
    }

    /**
     * @param string $googleToken
     * @return $this
     */
    public function setGoogleToken($googleToken)
    {
        $this->googleToken = $googleToken;

        return $this;
    }

    /**
     * @return string
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * @param string $googleId
     * @return $this
     */
    public function setGoogleId($googleId)
    {
        $this->googleId = $googleId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getChosenModule()
    {
        return $this->chosenModule;
    }

    /**
     * @param mixed $chosenModule
     * @return $this
     */
    public function setChosenModule($chosenModule)
    {
        $this->chosenModule = $chosenModule;

        return $this;
    }


    public function removeChosenModule($chosenModule)
    {
        if(($key = array_search($chosenModule, $this->chosenModule)) !== false) {
            unset($this->chosenModule[$key]);
        }

        return $this;
    }

    public function getCountModules()
    {
        return count($this->modulesUser);
    }

    /**
     * @return mixed
     */
    public function getTmpPassword()
    {
        return $this->tmpPassword;
    }

    /**
     * @param mixed $tmpPassword
     * @return $this
     */
    public function setTmpPassword($tmpPassword)
    {
        $this->tmpPassword = $tmpPassword;
        return $this;
    }

}

