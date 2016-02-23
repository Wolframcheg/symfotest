<?php

namespace AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class AdminController
 * @package AppBundle\Controller\Admin
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     * @Template("@App/admin/admin.html.twig")
     */
    public function adminAction()
    {
        return [];
    }
}
