<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class PassingTestController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * @Route("/ident-pass/{idModule}", name="ident_pass")
     */
    public function identPassAction(Request $request, $idModule)
    {
        $passManager = $this->container->get('app.pass_manager');
        $result = $passManager->identPass($idModule);
        var_dump($result);exit();
    }

    public function passAction($passId)
    {

    }
}
