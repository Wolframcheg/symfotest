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

        return $this->processResult($result);
    }

    /**
     * @Route("/pass-module/{idPass}", name="pass_module")
     * @Template()
     */
    public function passAction(Request $request, $idPass)
    {
        $passManager = $this->container->get('app.pass_manager');
        $result = $passManager->passModule($idPass);

        if($result['status'] == 'ok' && $request->isMethod('POST')){
            list($form, $question) = $result['content'];
                $form->bind($request);
                $data = $form->getData();
                $result = $this->get('app.pass_control')->process($data);
        }

        return $this->processResult($result);
    }

    /**
     * @Route("/pass-result/{idPass}", name="pass_result")
     * @Template()
     */
    public function passResultAction(Request $request, $idPass)
    {
        $passModule = $this->getDoctrine()->getRepository('AppBundle:PassModule')
                        ->getDonePassModuleByIdAndUser($idPass,$this->getUser()->getId())
                        ;

        if($passModule === null)
            throw new HttpException(403, 'You don\'t have permission for look this data');

        var_dump($passModule->getRating());exit();
    }

    private function processResult($result){
        switch ($result['status']) {
            case 'redirect_to_pass':
                return $this->redirectToRoute('pass_module',['idPass' => $result['content']], $result['code']);
            case 'redirect_to_result':
                return $this->redirectToRoute('pass_result',['idPass' => $result['content']], $result['code']);
            case 'error':
                throw new HttpException($result['code'], $result['content']);
            case 'ok':
                list($form, $question) = $result['content'];
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
