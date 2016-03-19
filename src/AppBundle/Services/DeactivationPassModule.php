<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 09.03.16
 * Time: 19:01
 */

namespace AppBundle\Services;


use AppBundle\Entity\ModuleUser;
use AppBundle\Entity\PassModule;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DeactivationPassModule
{
    private $doctrine;

    public function __construct(RegistryInterface $registryInterface)
    {
        $this->doctrine = $registryInterface;
    }

    public function deactivation(PassModule $passModule, $finishTime = null)
    {
        $em = $this->doctrine->getManager();
        $moduleUser = $em->getRepository('AppBundle:ModuleUser')
            ->find($passModule->getModuleUser());
        $module = $em->getRepository('AppBundle:Module')
            ->find($moduleUser->getModule());

        if ($finishTime) {
            $passModule->setTimeFinish($finishTime);
        }
        $passModule->setIsActive(false);
        $em->flush();

        if ($passModule->getPercentResult() >= $module->getPersentSuccess()) {
            $moduleUser->setStatus(ModuleUser::STATUS_SUCCESS);
            $moduleUser->setRating($passModule->getRating());
        } else if ($moduleUser->getCountPassModules() >= $module->getAttempts()){
                $moduleUser->setStatus(ModuleUser::STATUS_FAILED);
        }
        $em->flush();
    }

}
