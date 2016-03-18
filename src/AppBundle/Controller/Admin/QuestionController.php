<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Module;
use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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

        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($this->get('app.checkCount.answer')->checkCount($question)) {
                $question->setModule($module);
                $em->persist($question);
                $em->flush();
                return $this->redirectToRoute('create_question', array('idModule' => $idModule));
            }
        }

        return ['form' => $form->createView(),
                'idModule' => $idModule
        ];
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

        $originalAnswers = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($question->getAnswers() as $answer) {
            $originalAnswers->add($answer);
        }

        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isValid()) {
            if ($this->get('app.checkCount.answer')->checkCount($question)) {
                foreach ($originalAnswers as $answer) {
                    if (false === $question->getAnswers()->contains($answer)) {
                        $em->remove($answer);
                    }
                }
                $em->flush();
            }

            return $this->redirectToRoute('edit_question', array('id' => $id, 'idModule' => $idModule));
        }

        return ['form' => $form->createView(),
                'idModule' => $idModule
        ];
    }

    /**
     * @Route("/admin/question/remove/{id}/{idModule}", name="remove_question")
     * @Method("DELETE")
     */
    public function removeQuestionAction($id, $idModule)
    {
        $em = $this->getDoctrine()->getManager();

        $question = $em->getRepository('AppBundle:Question')
            ->find($id);

        $em->remove($question);
        $em->flush();

        return $this->redirectToRoute('show_question', array('idModule' => $idModule));

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
            ->findByModuleWithSorting($idModule);

        $form_delete = [];

        foreach ($question as $item) {
            $form_delete[$item->getId()] = $this->createFormDelete($item->getId(), $idModule)->createView();
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
    private function createFormDelete($id, $idModule)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('remove_question', ['id' => $id, 'idModule' => $idModule]))
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
