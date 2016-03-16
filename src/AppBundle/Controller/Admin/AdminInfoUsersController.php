<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\ModuleUser;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AdminInfoUsersController extends Controller
{
    /**
     * @Route("/admin/account/{id}", name="admin_account")
     * @Template("@App/account/showAccount.html.twig")
     */
    public function showAccountAction(User $id = null)
    {
        $em = $this->getDoctrine()->getManager();

        $modulesActive = $em->getRepository('AppBundle:ModuleUser')
            ->findModuleUserActive($id);

        $modulesSuccess = $em->getRepository('AppBundle:ModuleUser')
            ->findModuleUserSuccess($id);

        $modulesFailed = $em->getRepository('AppBundle:ModuleUser')
            ->findModuleUserFailed($id);

        return [
            'user' => $id,
            'modulesActive' => $modulesActive,
            'modulesSuccess' => $modulesSuccess,
            'modulesFailed' => $modulesFailed
        ];
    }
}
