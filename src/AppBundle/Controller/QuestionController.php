<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Answer;
use AppBundle\Entity\Module;
use AppBundle\Entity\Question;
use AppBundle\Entity\QuestionAnswer;
use AppBundle\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class QuestionController extends Controller
{
    /**
     * @Route("/admin/question/new/{module}", name="create_question")
     * @Template("@App/admin/question/createQuestion.html.twig")
     */
    public function createModuleAction(Request $request, Module $module)
    {
        $em = $this->getDoctrine()->getManager();

        $questionAnswer = new QuestionAnswer();
        $question = new Question();
        $answer = new Answer();

        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $question->setModule($module);
            $em->persist($module);
            $em->flush();

            return $this->redirectToRoute('show_module');
        }

        return ['form' => $form->createView()];
    }

}
