<?php
namespace AppBundle\Services;

use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminCreator
{
    private $doctrine;
    private $userPasswordEncoder;

    public function __construct(RegistryInterface $doctrine, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->doctrine = $doctrine;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function create($username, $email, $password)
    {
        $role = User::ROLE_ADMIN;

        $em = $this->doctrine->getManager();
        $user = $em->getRepository('AppBundle:User')->findBy(['username' => $username]);
        if (!$user)
            $user = new User();
        else $user = $user[0];

        $pass = $this->userPasswordEncoder->encodePassword($user, $password);
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($pass);
        $user->setRole($role);
        $em->persist($user);
        $em->flush();
    }
}