<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Module;
use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;


class QuestionController extends Controller
{
    /**
     * @Route("/admin/question/new/{idModule}", name="create_question")
     * @Template("@App/admin/question/createQuestion.html.twig")
     */
    public function createQuestionAction(Request $request, $idModule)
    {
        $em = $this->getDoctrine()->getManager();
        $module = $em->getRepository('AppBundle:Module')->find($idModule);
        $question = new Question();
        $answer = new Answer();

        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $question->setModule($module);
            $answer->setQuestion($question);
            $em->persist($question);
            $em->flush();

            return $this->redirectToRoute('create_question', array('idModule' => $idModule));
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/question/edit/{id}/{idModule}", name="edit_question")
     * @Template("@App/admin/question/editQuestion.html.twig")
     */
    public function editQuestionAction(Request $request, $id, $idModule)
    {
        $em = $this->getDoctrine()->getManager();

        $question = $em->getRepository('AppBundle:Question')
            ->find($id);

        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('show_question', array('idModule' => $idModule));
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/question/remove/{id}", name="remove_question")
     */
    public function removeQuestionAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $question = $em->getRepository('AppBundle:Question')
            ->find($id);

        $em->remove($question);
        $em->flush();

        return $this->redirectToRoute('show_question');

    }

    /**
     * @Route("/admin/question/show/{idModule}", name="show_question")
     * @Template("@App/admin/question/showQuestion.html.twig")
     */
    public function showQuestionAction($idModule)
    {
        $em = $this->getDoctrine()->getManager();

        $module = $em->getRepository('AppBundle:Module')->find($idModule);
        $question = $em->getRepository('AppBundle:Question')
            ->findBy(array('module' => $module));

        $form_delete = [];

        foreach ($question as $item) {
            $form_delete[$item->getId()] = $this->createFormDelete($item->getId())->createView();
        }

        return [
            'idModule' => $idModule,
            'questions' => $question,
            'form_remove' => $form_delete
        ];

    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createFormDelete($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('remove_question', ['id' => $id]))
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