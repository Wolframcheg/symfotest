<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 10:17
 */

namespace AppBundle\Repository;


use AppBundle\Entity\PassModule;
use AppBundle\Entity\Question;
use Doctrine\ORM\EntityRepository;

class QuestionRepository extends EntityRepository
{
    public function findByModuleWithSorting($moduleId)
    {
        return $this->createQueryBuilder('question')
            ->andWhere('question.module = :module')
            ->setParameter('module', $moduleId)
            ->orderBy('question.sort')
            ->getQuery()
            ->getResult()
            ;
    }

    public function getFirstQuestionForPass($idPassModule)
    {
        return $this->createQueryBuilder('question')
            ->leftJoin('question.module', 'module')
            ->leftJoin('module.modulesUser', 'module_user')
            ->leftJoin('module_user.passModules', 'pass_module')
            ->andWhere('pass_module.id = :idPassModule')
            ->setParameter('idPassModule', $idPassModule)
            ->orderBy('question.sort')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getNextQuestionForPass(PassModule $passModule, Question $currentQuestion)
    {
        return $this->createQueryBuilder('question')
            ->leftJoin('question.module', 'module')
            ->leftJoin('module.modulesUser', 'module_user')
            ->leftJoin('module_user.passModules', 'pass_module')
            ->orderBy('question.sort')
            ->andWhere('pass_module.id = :idPassModule')
            ->andWhere('question.sort >= :sortCurrentQuestion')
            ->andWhere('question.id <> :idCurrentQuestion')
            ->setParameter('idPassModule', $passModule->getId())
            ->setParameter('sortCurrentQuestion', $currentQuestion->getSort())
            ->setParameter('idCurrentQuestion', $currentQuestion->getId())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

}