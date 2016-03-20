<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 26.02.16
 * Time: 17:02
 */

namespace AppBundle\Services;


use AppBundle\Entity\Question;
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
    public function checkCount(Question $question)
    {
        $answers = $question->getAnswers();
        $countTrueAnswers = 0;

        foreach ($answers as $item) {
            $item->getCorrectly() ? $countTrueAnswers++ : null;
        }

        if ($countTrueAnswers > 3) {
            $this->session->getFlashBag()->add('notice',
                'You have to add not more than 3 true answers for question '.$question->getTextQuestion());

            return false;
        }

        return true;
    }

}
