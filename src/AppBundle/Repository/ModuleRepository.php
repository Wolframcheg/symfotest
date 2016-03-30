<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 9:27
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class ModuleRepository extends EntityRepository
{

    public function getFreeModulesForUserQuery(UserInterface $user)
    {
        return $this->createQueryBuilder('module')
            ->leftJoin('module.modulesUser mu WITH mu.user = :userId', false)
            ->where('mu IS NULL')
            ->setParameter('userId', $user->getId())
            ->orderBy('module.title', 'ASC');
    }

    public function getFreeModulesForUser(UserInterface $user)
    {
        return $this->getFreeModulesForUserQuery($user)
            ->getQuery()
            ->getResult();
    }

    public function findCountModules()
    {
        return $this->createQueryBuilder('module')
            ->select('COUNT(module.id) as count_m')
            ->getQuery()
            ->getOneOrNullResult();
    }

}