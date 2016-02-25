<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CategoryController
 * @package AppBundle\Controller\Admin
 */
class CategoryController extends Controller
{
    /**
     * @Route("/admin/category/new", name="create_category")
     * @Template("@App/admin/category/createCategory.html.twig")
     */
    public function createCategoryAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('show_category');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/category/edit/{id}", name="edit_category")
     * @Template("@App/admin/category/editCategory.html.twig")
     */
    public function editCategoryAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AppBundle:Category')
            ->find($id);
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('show_category');
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/category/remove/{id}", name="remove_category")
     */
    public function removeCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AppBundle:Category')
            ->find($id);

        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('show_category');

    }

    /**
     * @Route("/admin/category", name="show_category")
     * @Template("@App/admin/category/showCategory.html.twig")
     */
    public function showCategoryAction()
    {
        $em = $this->getDoctrine()->getManager();

        $category = $em->getRepository('AppBundle:Category')
            ->findBy([], ['title' => 'ASC']);

        $form_delete = [];

        foreach ($category as $item) {
            $form_delete[$item->getId()] = $this->createFormDelete($item->getId())->createView();
        }

        return [
            'categories' => $category,
            'form_remove' => $form_delete
        ];

    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createFormDelete($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('remove_category', ['id' => $id]))
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

