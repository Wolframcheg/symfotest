<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.03.16
 * Time: 18:35
 */

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ChoiceModulesController extends Controller
{
    /**
     * @Route("/choice-modules/{email}", name="choice_modules")
     * @Template("@App/choiceModules/choiceModules.html.twig")
     */
    public function showAccountAction(Request $request, $email)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')
            ->findOneBy(['email' => $email]);

        $modules = $em->getRepository('AppBundle:Module')
            ->findAll();
        if ($choice = $request->get('choice_module')) {
            $user->setChosenModule($choice);

            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return [
            'email' => $email,
            'modules' => $modules,

        ];
    }
}