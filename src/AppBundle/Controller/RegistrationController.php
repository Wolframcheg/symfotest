<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegitrationController
 * @package AppBundle\Controller
 */
class RegistrationController extends Controller
{
    /**
     * @Route("/registration", name="user_registration")
     * @Template("@App/registration/registration.html.twig")
     */
    public function registerAction(Request $request)
    {
        return $this->get('app.registration.user')
            ->registrationUser($request);
    }

    /**
     * @Route("/account/update-profile", name="update_profile")
     * @Template("@App/registration/updateRegistration.html.twig")
     */
    public function updateProfileAction(Request $request)
    {
        $user = $this->getUser();
        return $this->get('app.registration.user')
            ->updateRegistrationUser($request, $user);
    }

    /**
     * @Route("/account/after-soc-login", name="after_soc_login")
     */
    public function afterSocLogin()
    {
        $user = $this->getUser();
        if (!$user->getPassword())
            return $this->redirectToRoute('update_profile');

        return $this->redirectToRoute('account');
    }


    /**
     * @Route("/register/check_useremail", name="register_check_email")
     */
    public function checkUserEmail(Request $request)
    {
        $email = $request->request->get('email');

        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(array('email' => $email));

        if ($user) {

            return new Response('No', 200);
        }

        return new Response('Yes', 200);
    }

    /**
     * @Route("/registration/check_hash/{hash}/{email}", name="register_check_hash")
     * @Method("GET")
     */
    public function checkUserHash($hash, $email)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(array('email' => $email, 'hash' => $hash));

        if ($user) {
            $user->setIsActive(true);
            $user->setHash(null);
            $this->addFlash('notice', 'You have successfully passed registration confirmation');

            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $this->addFlash('notice', 'You haven\'t passed registration confirmation');

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/registration/recovery-password", name="recovery_password")
     * @Template("@App/registration/recoveryPassword.html.twig")
     */
    public function recoveryPassword(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $email = trim($request->get('email'));

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(['email' => $email]);
        if ($user && $user->isAccountNonLocked() == true) {
            $hash = $this->get('app.custom.mailer')->sendMailCheckRecovery($email);
            $user->setHash($hash);

            $em->flush();

            $this->addFlash('notice', 'Confirm your email');

            return $this->redirectToRoute('homepage');
        } elseif ($email && !$user) {

            $this->addFlash('notice', 'Email is incorrectly specified');

            return $this->redirectToRoute('homepage');
        } elseif ($user && $user->isAccountNonLocked() == false) {
            $this->addFlash('notice', 'You are blocked');

            return $this->redirectToRoute('homepage');
        } else {

            return [];
        }

    }

    /**
     * @Route("/registration/recovery/check_hash/{hash}/{email}", name="recovery_check_hash")
     * @Method("GET")
     */
    public function checkUserHashRecovery($hash, $email)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(array('email' => $email, 'hash' => $hash));

        if ($user) {
            $password = $this->get('app.custom.mailer')->sendMailRecovery($email);
            $newPassword = $this->get('security.password_encoder')
                ->encodePassword($user, $password);
            $user->setPassword($newPassword);
            $user->setHash(null);

            $em->flush();
            $this->addFlash('notice', 'The new password is sent to your email');

            return $this->redirectToRoute('homepage');
        }

        $this->addFlash('notice', 'You haven\'t passed recovery password confirmation');

        return $this->redirectToRoute('homepage');
    }
}
