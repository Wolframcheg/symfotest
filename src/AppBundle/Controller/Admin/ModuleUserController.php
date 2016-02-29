<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\ModuleUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ModuleUserType;

class ModuleUserController extends Controller
{
    /**
     * @Route("/admin/moduleUser/new/{idUser}", name="create_moduleUser")
     * @Template("@App/admin/moduleuser/createModuleUser.html.twig")
     */
    public function createCategoryAction(Request $request, $idUser)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')
            ->find($idUser);

        $moduleUser = new ModuleUser();
        $form = $this->createForm(ModuleUserType::class, $moduleUser);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $moduleUser->setUser($user);
            $em->persist($moduleUser);
            $em->flush();

            return $this->redirectToRoute('user_show');
        }

        return ['form' => $form->createView()];
    }
}
