<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 10:41
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class PassModuleRepository extends EntityRepository
{
    public function getModuleByIdAndUser($idPassModule, $idUser)
    {
        return $this->createQueryBuilder('pass_module')
            ->leftJoin('pass_module.moduleUser', 'module_user')
            ->leftJoin('module_user.user', 'user')
            ->andWhere('pass_module.id = :idPassModule')
            ->andWhere('user.id = :idUser')
            ->setParameter('idPassModule', $idPassModule)
            ->setParameter('idUser', $idUser)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getDonePassModuleByIdAndUser($idPassModule, $idUser)
    {
        return $this->createQueryBuilder('pass_module')
            ->leftJoin('pass_module.moduleUser', 'module_user')
            ->leftJoin('module_user.user', 'user')
            ->andWhere('pass_module.id = :idPassModule')
            ->andWhere('user.id = :idUser')
            ->andWhere('pass_module.isActive = :isActive')
            ->setParameter('idPassModule', $idPassModule)
            ->setParameter('idUser', $idUser)
            ->setParameter('isActive', false)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function findAjax($module)
    {
        return $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.moduleUser = :module')
            ->setParameter('module', $module)
            ->getQuery()
            ->getResult();
    }

}