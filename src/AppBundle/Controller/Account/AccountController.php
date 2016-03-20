<?php

namespace AppBundle\Controller\Account;

use AppBundle\Entity\ModuleUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends Controller
{
    /**
     * @Route("/account", name="account")
     * @Template("@App/Account/showAccount.html.twig")
     */
    public function showAccountAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin');
        }

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $modules = $em->getRepository('AppBundle:ModuleUser')
            ->findModules($user);

        return [
            'user' => $user,
            'modules' => $modules
        ];
    }
}
