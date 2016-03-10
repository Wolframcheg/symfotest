<?php

namespace AppBundle\Controller\Account;

use AppBundle\Entity\ModuleUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AccountController extends Controller
{
    /**
     * @Route("/account", name="account")
     * @Template("@App/account/showAccount.html.twig")
     */
    public function showAccountAction()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $modules = $em->getRepository('AppBundle:ModuleUser')
            ->findBy(['user' => $user]);

        $finishModule = $em->getRepository('AppBundle:ModuleUser')
            ->findModuleUserFinish($user);

        $startModules = $em->getRepository('AppBundle:ModuleUser')
            ->findModuleUserStart($user);

        $x = '';
        return [
            'modules' => $modules,
            'finishModules' => $finishModule,
            'startModules' => $startModules
        ];
    }
}
