<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 13:03
 */

namespace AppBundle\Services;


use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\RegistryInterface;
use AppBundle\Form\UserType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class Registration
 * @package AppBundle\Services
 */
class Registration
{
    /**
     * @var RegistryInterface
     */
    protected $doctrine;
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;
    /**
     * @var RouterInterface
     */
    protected $router;
    /**
     * @var ValidatorInterface
     */
    protected $validator;
    /**
     * @var UserPasswordEncoder
     */
    protected $passwordEncoder;

    /**
     * @param RegistryInterface $doctrine
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoder $passwordEncoder
     */
    public function __construct(RegistryInterface $doctrine,
                                FormFactoryInterface $formFactory,
                                RouterInterface $router,
                                ValidatorInterface $validator,
                                UserPasswordEncoder $passwordEncoder
                            )
    {
        $this->doctrine = $doctrine;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function registrationUser(Request $request)
    {
        $em = $this->doctrine->getManager();

        $role = User::ROLE_USER;
        $user = new User();
        $form = $this->formFactory->create(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $password = $this->passwordEncoder
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRole($role);

            $em->persist($user);
            $em->flush();

            return new RedirectResponse($this->router->generate('homepage'));
        }

        return ['form' => $form->createView()];
    }
}