<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Module;
use AppBundle\Form\ModuleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class ModuleController extends Controller
{
    /**
     * @Route("/admin/module/new", name="create_module")
     * @Template("@App/admin/module/createModule.html.twig")
     */
    public function createModuleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $module = new Module();
        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($module);
            $em->flush();

            return $this->redirectToRoute('create_question', array('idModule' => $module->getId()));

        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/module/edit/{id}", name="edit_module")
     * @Template("@App/admin/module/editModule.html.twig")
     */
    public function editModuleAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $module = $em->getRepository('AppBundle:Module')
            ->find($id);
        $form = $this->createForm(ModuleType::class, $module);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('show_module');
        }

        return ['module' => $module,
                'form' => $form->createView()];
    }

    /**
     * @Route("/admin/module/remove/{id}", name="remove_module")
     * @Method("DELETE")
     */
    public function removeCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $module = $em->getRepository('AppBundle:Module')
            ->find($id);

        $em->remove($module);
        $em->flush();

        return $this->redirectToRoute('show_module');

    }

    /**
     * @Route("/admin/module", name="show_module")
     * @Template("@App/admin/module/showmodule.html.twig")
     */
    public function showModuleAction()
    {
        $em = $this->getDoctrine()->getManager();

        $module = $em->getRepository('AppBundle:Module')
            ->findBy([],['createdAt' => 'DESC']);

        $form_delete = [];

        foreach ($module as $item) {
            $form_delete[$item->getId()] = $this->createFormDelete($item->getId())->createView();
        }

        return [
            'modules' => $module,
            'form_remove' => $form_delete
        ];

    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createFormDelete($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('remove_module', ['id' => $id]))
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
