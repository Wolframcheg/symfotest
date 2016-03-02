<?php
namespace AppBundle\Services;

use AppBundle\Entity\Comment;
use AppBundle\Entity\ModuleUser;
use AppBundle\Entity\PassModule;
use AppBundle\Form\CommentType;
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
            $newPassModule = $this->createModule($moduleUser);
            return $this->generateOutput('redirect_to_pass', 301, $newPassModule->getId());
        }

        $nowDate = new \DateTime();
        $dateEstimate = $lastPass->getTimeStart()
            ->modify("+{$lastPass->getTimePeriod()} minutes");

        if ($nowDate > $dateEstimate) {
            $lastPass->setIsActive(false);
            $this->doctrine->getEntityManager()->flush();
            $this->identPass($idModule);
        }

        return $this->generateOutput('redirect_to_pass', 301, $lastPass->getId());
    }

    private function createModule(ModuleUser $moduleUser)
    {
        $passModule = $this->createModuleInstance();
        $passModule->setModuleUser($moduleUser);
        $passModule->setTimePeriod($moduleUser->getModule()->getTime());

        $this->doctrine->getEntityManager()->persist($passModule);
        $this->doctrine->getEntityManager()->flush();
        return $passModule;
    }

    private function createModuleInstance()
    {
        return new PassModule();
    }

    private function generateOutput($status, $code, $message)
    {
        return [
            'status' => $status,
            'code' => $code,
            'content' => $message
        ];
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


}