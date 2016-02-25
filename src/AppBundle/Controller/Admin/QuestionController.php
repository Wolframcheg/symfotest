<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Module;
use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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
            $question->setModule($module);
            $em->persist($question);
            $em->flush();

            return $this->redirectToRoute('create_question', array('idModule' => $idModule));
        }

        return ['form' => $form->createView()];
    }

    /**
     * @Route("/admin/question/edit/{idModule}", name="edit_question")
     * @Template("@App/admin/question/editQuestion.html.twig")
     */
    public function editQuestionAction(Request $request, $idModule)
    {
        $em = $this->getDoctrine()->getManager();
        $module = $em->getRepository('AppBundle:Module')->find($idModule);
        $question = $em->getRepository('AppBundle:Question')
            ->findBy(array('module' => $module));

        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('create_question', array('idModule' => $idModule));
        }

        return ['form' => $form->createView()];
    }
}
