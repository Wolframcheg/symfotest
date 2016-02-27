<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 26.02.16
 * Time: 17:02
 */

namespace AppBundle\Services;


use AppBundle\Entity\Question;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;

class CheckCountAnswers
{
    private $route;
    private $session;

    public function __construct(RouterInterface $routerInterface, Session $session)
    {
        $this->route = $routerInterface;
        $this->session = $session;
    }
    public function checkCountInsert(Question $question, $idModule)
    {
        $answers = $question->getAnswers();

        if (count($answers) < 2) {
            $this->session->getFlashBag()->add('notice', 'You have to add not less than 2 answers');

            return new Response($this->route->generate('create_question', array('idModule' => $idModule)));
        }

        return true;
    }

    public function checkCountEdit(Question $question, $id, $idModule)
    {
        $answers = $question->getAnswers();

        if (count($answers) < 2) {
            $this->session->getFlashBag()->add('notice', 'You have to add not less than 2 answers');

            return new Response($this->route->generate('edit_question', array('id' => $id, 'idModule' => $idModule)));
        }

        return true;
    }
}