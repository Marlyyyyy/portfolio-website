<?php
/**
 * Created by PhpStorm.
 * User: Marci
 * Date: 2014.08.29.
 * Time: 13:58
 */

namespace Marton\PortfolioBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository{

    public function findAllProjects(){

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb ->select('p')
            ->from('MartonPortfolioBundle:Project', 'p')
            ->orderBy('p.order', 'DESC');

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
    }

}