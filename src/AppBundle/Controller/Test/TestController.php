<?php

namespace AppBundle\Controller\Test;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class TestController extends Controller
{
    /**
     * @Route("/test", name="test")
     * @Template("@App/test/test.html.twig")
     */
    public function adminAction()
    {
        return [];
    }
}
