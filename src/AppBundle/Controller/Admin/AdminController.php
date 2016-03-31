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
        $em = $this->getDoctrine()->getManager();

        $countUsers = $em->getRepository('AppBundle:User')->findCountUsers();
        $countModules = $em->getRepository('AppBundle:Module')->findCountModules();
        $countCategories = $em->getRepository('AppBundle:Category')->findCountCategories();
        $countQuestions = $em->getRepository('AppBundle:Question')->findCountQuestions();

        return [
            'count_users' => $countUsers['count_u'],
            'count_modules' => $countModules['count_m'],
            'count_categories' => $countCategories['count_c'],
            'count_questions' => $countQuestions['count_q']
        ];
    }
}
