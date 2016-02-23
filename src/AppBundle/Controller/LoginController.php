<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class LoginController
 * @package AppBundle\Controller
 */
class LoginController extends Controller
{
    /**
     * @Route("/login", name="login_route")
     * @Template("@App/login/login.html.twig")
     *
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return [
                'last_username' => $lastUsername,
                'error'         => $error,
            ];
    }
}
