<?php

namespace AppBundle\Controller\Test;

use AppBundle\Entity\ModuleUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * Class TestController
 * @package AppBundle\Controller\Test
 */
class TestController extends Controller
{
    /**
     * @Route("/test", name="show_test")
     * @Template("@App/test/test.html.twig")
     */
    public function showTestAction()
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();

        $modules = $em->getRepository('AppBundle:ModuleUser')
            ->findModuleUserActive($user);

        return [
            'modules' => $modules
        ];
    }
}
