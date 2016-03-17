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
     * @Template("@App/account/showAccount.html.twig")
     */
    public function showAccountAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $request->get('module') ? $module = $request->get('module') : $module = null;

        if ($module) {
            $moduleAjax = $em->getRepository('AppBundle:PassModule')
                ->findAjax($module);

            return new Response(json_encode(['moduleAjax' => $moduleAjax]));
        }

        $modules = $em->getRepository('AppBundle:ModuleUser')
            ->findModules($user);

        return [
            'user' => $user,
            'modules' => $modules
        ];
    }
}
