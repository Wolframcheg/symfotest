<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * User controller.
 *
 * @Route("/admin/user")
 */
class UserController extends Controller
{
    /**
     * Lists all User entities.
     *
     * @Route("", name="user_show")
     * @Method("GET")
     * @Template("@App/admin/user/showUser.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findUsersWithoutRole(User::ROLE_ADMIN);

        $form_delete = [];

        foreach ($users as $item) {
            $form_delete[$item->getId()] = $this->createFormDelete($item->getId())->createView();
        }

        return [
            'users' => $users,
            'form_remove' => $form_delete
        ];
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/{id}/edit", name="edit_user")
     * @Method({"GET", "POST"})
     * @Template("@App/admin/user/editUser.html.twig")
     */
    public function editAction(Request $request, User $user)
    {
        $editForm = $this->createForm('AppBundle\Form\UpdateUserType', $user);

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('user_show');
        }

        return [
            'user' => $user,
            'form' => $editForm->createView(),
        ];
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createFormDelete($user->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_show');
    }

    /**
     * Creates a form to delete a User entity.
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createFormDelete($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, ['label' => ' ',
                'attr' => [
                    'class' => 'glyphicon glyphicon-remove btn-link',
                    'onclick' => 'return confirm("Are you sure?")'
                ]])
            ->getForm();
    }
}