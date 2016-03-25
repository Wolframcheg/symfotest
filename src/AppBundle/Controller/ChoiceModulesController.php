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
     * @Route("/account/choice-modules", name="choice_modules")
     * @Template("@App/choiceModules/choiceModules2.html.twig")
     */
    public function showAccountAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $modules = $em->getRepository('AppBundle:Module')
            ->getFreeModulesForUser($user);
      //  $arr = $user->getChosenModule() ? $user->getChosenModule() : [];

        if ($request->getMethod() == 'POST') {
            $choice = $request->get('choice_module');
//            foreach ($choice as $item) {
//                array_push($arr, $item);
//            }
//            $user->setChosenModule($arr);
            $user->setChosenModule($choice);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return [
            'modules' => $modules,
            'user'=>$user

        ];
    }
}
