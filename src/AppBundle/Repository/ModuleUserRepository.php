<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 10:06
 */

namespace AppBundle\Repository;


use AppBundle\Entity\ModuleUser;
use Doctrine\ORM\EntityRepository;

class ModuleUserRepository extends EntityRepository
{
    public function findModuleUserStart($user)
    {
        return $this->createQueryBuilder('mu')
            ->select('mu, m, p')
            ->join('mu.passModules', 'p')
            ->join('mu.module', 'm')
            ->where('mu.user = :user')
            ->andWhere('mu.status = :active')
            ->setParameter('user', $user)
            ->setParameter('active', ModuleUser::STATUS_ACTIVE)
            ->orderBy('p.timeFinish', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findModuleUserFinish($user)
    {
        return $this->createQueryBuilder('mu')
            ->select('mu, m, p')
            ->join('mu.passModules', 'p')
            ->join('mu.module', 'm')
            ->where('mu.user = :user')
            ->andWhere('mu.status <> :active')
            ->setParameter('user', $user)
            ->setParameter('active', ModuleUser::STATUS_ACTIVE)
            ->orderBy('p.timeFinish', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findModuleUser($user)
    {
        return $this->createQueryBuilder('mu')
            ->where('mu.user = :user')
            ->andWhere('mu.status = :active')
            ->setParameter('user', $user)
            ->setParameter('active', ModuleUser::STATUS_ACTIVE)
            ->getQuery()
            ->getResult();
    }
}