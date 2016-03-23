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
use AppBundle\Form\UpdateUserSocialNetType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
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

    protected $template;

    protected $mailer;
    protected $session;

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
                                UserPasswordEncoder $passwordEncoder,
                                MailerService $mailerService,
                                Session $sessionService
                            )
    {
        $this->doctrine = $doctrine;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->validator = $validator;
        $this->passwordEncoder = $passwordEncoder;
        $this->mailer = $mailerService;
        $this->session = $sessionService;
    }

    /**
     * @param Request $request
     * @return array|RedirectResponse
     */
    public function registrationUser(Request $request)
    {
        $em = $this->doctrine->getManager();

        $user = new User();
        $form = $this->formFactory->create(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $password = $this->passwordEncoder
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $hash = $this->mailer->sendMail($user->getEmail());
            $user->setHash($hash);

            $em->persist($user);
            $em->flush();
            $this->session->getFlashBag()->add('notice',
                'Confirm your email!!!');

            return new RedirectResponse($this->router->generate('choice_modules', ['email' => $user->getEmail()]));
        }

        return ['form' => $form->createView()];
    }

    public function updateRegistrationUser(Request $request, User $user)
    {
        $em = $this->doctrine->getManager();
        $form = $this->formFactory->create(UpdateUserSocialNetType::class, $user);
        $originalPassword = $user->getPassword();
        $form->handleRequest($request);

        if ($form->isValid()) {
            if (!empty($user->getPlainPassword())) {
                $password = $this->passwordEncoder
                    ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
            } else {
                $user->setPassword($originalPassword);
            }

            $user->setIsActive(true);

            $em->flush();

            return new RedirectResponse($this->router->generate('choice_modules', ['email' => $user->getEmail()]));
        }

        return ['form' => $form->createView()];
    }
}