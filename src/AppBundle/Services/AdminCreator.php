<?php
namespace AppBundle\Services;

use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminCreator
 * @package AppBundle\Services
 */
class AdminCreator
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * @param RegistryInterface $doctrine
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(RegistryInterface $doctrine, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->doctrine = $doctrine;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @param $email
     * @param $password
     * @param $firstName
     * @param $lastName
     */
    public function create($email, $password, $firstName, $lastName)
    {
        $role = User::ROLE_ADMIN;

        $em = $this->doctrine->getManager();
        $user = $em->getRepository('AppBundle:User')->findBy(['email' => $email]);
        if (!$user)
            $user = new User();
        else $user = $user[0];

        $pass = $this->userPasswordEncoder->encodePassword($user, $password);
        $user->setEmail($email);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setPassword($pass);
        $user->setRole($role);
        $user->setIsActive(true);
        $em->persist($user);
        $em->flush();
    }
}