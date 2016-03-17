<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\ModuleUser;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminInfoUsersController extends Controller
{
    /**
     * @Route("/admin/account/{id}", name="admin_account")
     * @Template("@App/account/showAccount.html.twig")
     */
    public function showAccountAction(Request $request, User $id = null)
    {
        $em = $this->getDoctrine()->getManager();

        $request->get('module') ? $module = $request->get('module') : $module = null;

        if ($module) {
            $moduleAjax = $em->getRepository('AppBundle:PassModule')
                ->findAjax($module);

            return new Response(json_encode(['moduleAjax' => $moduleAjax]));
        }

        $modules = $em->getRepository('AppBundle:ModuleUser')
            ->findModules($id);

        return [
            'user' => $id,
            'modules' => $modules
        ];
    }
}
