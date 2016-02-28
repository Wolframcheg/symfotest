<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 10:17
 */

namespace AppBundle\Repository;


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
}