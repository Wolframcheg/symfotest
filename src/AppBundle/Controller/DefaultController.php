<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template("@App/default/index.html.twig")
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/module-user-info", name="module_user_info")
     */
    public function moduleUserInfoAction(Request $request)
    {
        $request->get('module') ? $module = $request->get('module') : $module = null;
        $em = $this->getDoctrine()->getManager();

        if ($module && $request->isXmlHttpRequest()) {
            $moduleAjax = $em->getRepository('AppBundle:PassModule')
                ->findAjax($module);

            return new Response(json_encode(['moduleAjax' => $moduleAjax]));
        }
    }
}
