<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\ModuleUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MassActionController extends Controller
{
    /**
     * @Route("/admin/mass-action", name="mass_action")
     * @Template("@App/admin/massAction/massAction.html.twig")
     */
    public function showAccountAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $modules = $em->getRepository('AppBundle:Module')
            ->findAll();

        if ($module = $request->get('module')) {
            $users = $em->getRepository('AppBundle:User')
                ->findChoiceModules($module);

            return new Response(json_encode(['users' => $users]));
        }

        if ($choice = $request->get('users_choice')) {
            $moduleHidden = $em->getRepository('AppBundle:Module')
                ->find($request->get('moduleHidden'));
            for ($i = 0; $i < count($choice); $i++) {
                $moduleUser = new ModuleUser();
                $moduleUser->setModule($moduleHidden);
                $user = $em->getRepository('AppBundle:User')
                    ->find($choice[$i]);
                $moduleUser->setUser($user);

                $em->persist($moduleUser);
            }

            $em->flush();

            return $this->redirectToRoute('admin');
        }

        return [
            'modules' => $modules
        ];
    }
}
