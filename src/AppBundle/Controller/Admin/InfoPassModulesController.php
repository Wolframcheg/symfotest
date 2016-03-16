<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class InfoPassModulesController extends Controller
{
    /**
     * @Route("/admin/infoPassModules", name="info_pass")
     * @Template("@App/admin/infopassmodules/infoPassModules.html.twig")
     */
    public function infoPassModulesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $allmodules = $em->getRepository('AppBundle:Module')
            ->findAll();
        $request->get('mod') ? $module = $request->get('mod') : $module = null;

        if ($module) {
            $modulesUser = $em->getRepository('AppBundle:ModuleUser')
                ->findInfoPassModules($module);

            return new Response(json_encode(['modulesUser' => $modulesUser]));
        }


        return ['modules' => $allmodules];
    }
}
