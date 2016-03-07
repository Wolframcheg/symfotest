<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;


class PassingTestController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * @Route("/ident-pass/{idModule}", name="ident_pass")
     */
    public function identPassAction(Request $request, $idModule)
    {
        $passManager = $this->container->get('app.pass_manager');
        $result = $passManager->identPass($idModule);

        switch ($result['status']) {
            case 'redirect_to_pass':
                return $this->redirectToRoute('pass_module',['idPass' => $result['content']], $result['code']);
            case 'error':
                throw new HttpException($result['code'], $result['content']);
            default:
                throw new HttpException(500, 'Ooops something wrong');
        }
    }

    /**
     * @Route("/pass-module/{idPass}", name="pass_module")
     * @Template()
     */
    public function passAction(Request $request, $idPass)
    {
        $passManager = $this->container->get('app.pass_manager');
        $result = $passManager->passModule($idPass);

        if($result['status'] == 'ok'){
            list($form, $question) = $result['content'];

            if($request->isMethod('POST')){
                $form->bind($request);
                $data = $form->getData();
                $res = $this->get('app.check.answers')->checkAnswers($data);
            };

        }


        switch ($result['status']) {
            case 'error':
                throw new HttpException($result['code'], $result['content']);
            case 'ok':
                return [
                    'data' => [
                        'form' => $form->createView(),
                        'question' => $question
                    ]
                ];
            default:
                throw new HttpException(500, 'Ooops something wrong');
        }
    }
}
