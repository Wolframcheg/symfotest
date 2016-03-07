<?php
namespace AppBundle\Services;

use AppBundle\Entity\Comment;
use AppBundle\Entity\ModuleUser;
use AppBundle\Entity\PassModule;
use AppBundle\Form\AnswerForPassType;
use AppBundle\Form\CommentType;
use AppBundle\Traits\GenerateOutput;
use Faker\Provider\cs_CZ\DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Proxies\__CG__\AppBundle\Entity\Module;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\CacheWarmer\WarmableInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PassManager
{
    use GenerateOutput;

    protected $doctrine;
    protected $formFactory;
    protected $router;
    protected $tokenStorage;

    public function __construct(RegistryInterface $doctrine,
                                FormFactoryInterface $formFactory,
                                TokenStorageInterface $tokenStorage
    )
    {
        $this->doctrine = $doctrine;
        $this->formFactory = $formFactory;
        $this->tokenStorage = $tokenStorage;
    }

    public function identPass($idModule)
    {
        $user = $this->getUser();

        $moduleUser = $this->doctrine->getRepository('AppBundle:ModuleUser')
            ->findOneBy([
                'user' => $user->getId(),
                'module' => $idModule
            ]);

        if (null === $moduleUser) {
            return $this->generateOutput('error', 403, 'You do not have access to this module');
        }

        if ($moduleUser->getAttempts() == $moduleUser->getModule()->getAttempts())
            return $this->generateOutput('error', 403, 'Your attempts are exhausted');

        $lastPass = $moduleUser->getPassModules()->last();

        if (0 == $moduleUser->getCountPassModules() || !$lastPass->getIsActive()) {
            $newPassModule = $this->createPassModule($moduleUser);
            return $this->generateOutput('redirect_to_pass', 301, $newPassModule->getId());
        }

        $time_residue = $this->checkDatePass($lastPass);

        if (!$time_residue)
            $this->identPass($idModule);

        return $this->generateOutput('redirect_to_pass', 301, $lastPass->getId());
    }


    public function checkDatePass(PassModule $pass)
    {
        $nowDate = new \DateTime();
        $dateEstimate = $pass->getTimeStart()
            ->modify("+{$pass->getTimePeriod()} minutes");

        if($nowDate > $dateEstimate){
            $pass->setIsActive(false);
            //$pass->setTimeFinish($dateEstimate);
            $this->doctrine->getEntityManager()->flush();
            return false;
        }

        $interval = date_diff($nowDate, $dateEstimate);
        return $interval->format('%I:%S');
    }

    private function createPassModule(ModuleUser $moduleUser)
    {
        $passModule = $this->createPassModuleInstance();
        $passModule->setModuleUser($moduleUser);
        $passModule->setTimePeriod($moduleUser->getModule()->getTime());

        $this->doctrine->getEntityManager()->persist($passModule);
        $this->doctrine->getEntityManager()->flush();
        return $passModule;
    }

    private function createPassModuleInstance()
    {
        return new PassModule();
    }

    protected function getUser()
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

    public function passModule($idPassModule)
    {
        $user = $this->getUser();

        $passModule = $this->doctrine->getRepository('AppBundle:PassModule')
                    ->getModuleByIdAndUser($idPassModule, $user->getId());

        if (null === $passModule) {
            return $this->generateOutput('error', 403, 'You do not have access to this pass');
        }

        if (null === $passModule->getCurrentQuestion()) {
            $firstQuestionForPass = $this->doctrine->getRepository('AppBundle:Question')
                ->getFirstQuestionForPass($passModule->getId());

            if(null === $firstQuestionForPass)
                return $this->generateOutput('error', 500, 'This module does not have any questions ;(');

            $passModule->setCurrentQuestion($firstQuestionForPass);
            $this->doctrine->getEntityManager()->flush();
            $this->passModule($idPassModule);
        }

        if(!($passModule->getIsActive())){
            return $this->generateOutput('error', 403, 'This pass is overdue ;(');
        }

        $time_residue = $this->checkDatePass($passModule);

        if (!$time_residue)
            return $this->generateOutput('error', 403, 'This pass is overdue ;(');


        $currentQuestion = $passModule->getCurrentQuestion();
        $form = $this->formFactory->create(AnswerForPassType::class, null, [
            'method' => Request::METHOD_POST,
            'idQuestion' => $currentQuestion->getId(),
            'idPassModule' => $idPassModule,
            'answers' => $currentQuestion->getAnswers()->toArray()
        ]);
        $form->add('submit', SubmitType::class, ['label' => 'Save', 'attr' => [ 'class' => 'btn btn-primary' ]]);

//        $numberQuestion = $this->doctrine->getRepository('AppBundle:Question')
//            ->getNumberCurrentQuestionWithSort($passModule);

        return $this->generateOutput('ok', 200, [
            $form,
            $currentQuestion,
            $time_residue,
            $passModule->getModuleUser()->getModule()->getCountQuestions()
        ]);
    }


}