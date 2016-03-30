<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 23.02.16
 * Time: 9:45
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findCountCategories()
    {
        return $this->createQueryBuilder('category')
            ->select('COUNT(category.id) as count_c')
            ->getQuery()
            ->getOneOrNullResult();
    }
}