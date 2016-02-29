<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\ModuleUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ModuleUserType;

/**
 * Class ModuleUserController
 * @package AppBundle\Controller\Admin
 */
class ModuleUserController extends Controller
{
    /**
     * @Route("/admin/moduleUser/new/{idUser}", name="create_moduleUser")
     * @Template("@App/admin/moduleuser/createModuleUser.html.twig")
     */
    public function createModuleUserAction(Request $request, $idUser)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')
            ->find($idUser);
        $modulesUser = $em->getRepository('AppBundle:ModuleUser')
            ->findBy(['user' => $user]);

        $moduleUser = new ModuleUser();
        $form = $this->createForm(ModuleUserType::class, $moduleUser, ['user' => $user]);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $user->setIsActive(true);
            $moduleUser->setUser($user);
            $moduleUser->setAttempts(0);
            $moduleUser->setIsActive(true);
            $em->persist($moduleUser);
            $em->flush();

            return $this->redirectToRoute('user_show');
        }

        $form_delete = [];

        foreach ($modulesUser as $item) {
            $form_delete[$item->getModule()->getId()] = $this->createFormDelete($idUser, $item->getModule()->getId())->createView();
        }

        return [
                'modulesUser' => $modulesUser,
                'form_remove' => $form_delete,
                'form' => $form->createView()
        ];
    }

    /**
     * @Route("/admin/moduleUser/remove/{idUser}/{idModule}", name="remove_moduleUser")
     */
    public function removeModuleAction($idUser, $idModule)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')
            ->find($idUser);
        $module = $em->getRepository('AppBundle:Module')
            ->find($idModule);
        $moduleUser = $em->getRepository('AppBundle:ModuleUser')
            ->findOneBy(['user' => $user, 'module' => $module]);

        $em->remove($moduleUser);
        $em->flush();

        return $this->redirectToRoute('create_moduleUser', ['idUser' => $idUser]);
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createFormDelete($idUser, $idModule)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('remove_moduleUser', ['idUser' => $idUser, 'idModule' => $idModule]))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, [
                'label' => ' ',
                'attr' => [
                    'class' => 'glyphicon glyphicon-remove btn-link',
                    'onclick' => 'return confirm("Are you sure?")'
                ]
            ])
            ->getForm();
    }
}
