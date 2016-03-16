<?php

namespace AppBundle\Controller\Account;

use AppBundle\Entity\ModuleUser;
use AppBundle\Entity\User;
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

        $modulesActive = $em->getRepository('AppBundle:ModuleUser')
            ->findModuleUserActive($user);

        $modulesSuccess = $em->getRepository('AppBundle:ModuleUser')
            ->findModuleUserSuccess($user);

        $modulesFailed = $em->getRepository('AppBundle:ModuleUser')
            ->findModuleUserFailed($user);

        return [
            'user' => $user,
            'modulesActive' => $modulesActive,
            'modulesSuccess' => $modulesSuccess,
            'modulesFailed' => $modulesFailed
        ];
    }
}
