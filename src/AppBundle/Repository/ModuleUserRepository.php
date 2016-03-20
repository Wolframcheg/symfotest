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
    public function findModuleUserFailed($user)
    {
        return $this->createQueryBuilder('mu')
            ->select('mu, m, p')
            ->join('mu.passModules', 'p')
            ->join('mu.module', 'm')
            ->where('mu.user = :user')
            ->andWhere('mu.status = :failed')
            ->setParameter('user', $user)
            ->setParameter('failed', ModuleUser::STATUS_FAILED)
            ->getQuery()
            ->getResult();
    }

    public function findModuleUserSuccess($user)
    {
        return $this->createQueryBuilder('mu')
            ->select('mu, m, p')
            ->join('mu.passModules', 'p')
            ->join('mu.module', 'm')
            ->where('mu.user = :user')
            ->andWhere('mu.status = :success')
            ->setParameter('user', $user)
            ->setParameter('success', ModuleUser::STATUS_SUCCESS)
            ->getQuery()
            ->getResult();
    }

    public function findModuleUserActive($user)
    {
        return $this->createQueryBuilder('mu')
            ->select('mu, m')
            ->join('mu.module', 'm')
            ->where('mu.user = :user')
            ->andWhere('mu.status = :active')
            ->setParameter('user', $user)
            ->setParameter('active', ModuleUser::STATUS_ACTIVE)
            ->getQuery()
            ->getResult();
    }

    public function findInfoPassModules($module)
    {
        return $this->createQueryBuilder('mu')
            ->select('mu, m, u, p')
            ->join('mu.module', 'm')
            ->join('mu.user', 'u')
            ->join('mu.passModules', 'p')
            ->where('mu.status <> :active')
            ->andWhere('mu.module = :module')
            ->orderBy('mu.rating', 'DESC')
            ->setParameter('active', ModuleUser::STATUS_ACTIVE)
            ->setParameter('module', $module)
            ->getQuery()
            ->getResult();
    }

    public function findModules($user)
    {
        return $this->createQueryBuilder('mu')
            ->select('mu, m, p')
            ->leftJoin('mu.passModules', 'p')
            ->leftJoin('mu.module', 'm')
            ->where('mu.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }


}