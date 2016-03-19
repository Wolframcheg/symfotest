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
     * @Route("/registrationNet", name="net_registration")
     * @Template("@App/registration/updateRegistration.html.twig")
     */
    public function registerSocialNetAction(Request $request)
    {
        $user = $this->getUser();

        if ($user) {
            if ($user->getIsReg() === false) {
                return $this->get('app.registration.user')
                    ->updateRegistrationUser($request, $user);
            }

            return $this->redirectToRoute('account');
        }

        return $this->redirectToRoute('homepage');
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

             $user->setIsReg(true);
             $user->setHash(null);
             $this->addFlash('notice', 'You have successfully passed registration confirmation');

             $em->flush();

             return $this->redirectToRoute('homepage');
         }

         $this->addFlash('notice', 'You haven\'t passed registration confirmation');

         return $this->redirectToRoute('homepage');
    }
}
