<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 19.02.16
 * Time: 17:37
 */

namespace AppBundle\OAuthProvider;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LoginUserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface
{
    /** @var  EntityManager $em */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Loads the user by a given UserResponseInterface object.
     *
     * @param UserResponseInterface $response
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $em = $this->em;
        $type = $response->getResourceOwner()->getName();

        $user = $em->getRepository('AppBundle:User')->findOneBy(['email' => $response->getEmail()]);
        if ($user === null) {
            $user = new User();
            $user->setEmail($response->getEmail())
                ->setFirstName($response->getFirstName())
                ->setLastName($response->getLastName())
                ->setIsActive(true);
            $em->persist($user);
            $em->flush();

        }
        if ($type === 'facebook') {
            $user->setFacebookToken($response->getAccessToken())
                ->setFacebookId($response->getUsername());
        }
        if ($type === 'google') {
            $user->setGoogleToken($response->getAccessToken())
                ->setGoogleId($response->getUsername());
        }
        $em->flush();

        return $user;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @see UsernameNotFoundException
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $user = $this->em->getRepository('AppBundle:User')->findOneBy(['email' => $username]);
        if (!$user) {
            throw new UsernameNotFoundException(sprintf("User '%s' not found.", $username));
        }

        return $user;
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class instanceof User;
    }

    /**
     * Connects the response the the user object.
     *
     * @param UserInterface $user The user object
     * @param UserResponseInterface $response The oauth response
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
    }

}