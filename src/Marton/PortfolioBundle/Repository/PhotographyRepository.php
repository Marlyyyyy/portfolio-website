<?php
/**
 * User: Marci
 * Date: 20/07/14
 * Time: 14:20
 */

namespace Marton\PortfolioBundle\Repository;

use Doctrine\ORM\EntityRepository;


class PhotographyRepository extends EntityRepository{

    public function findAllAlbums(){

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb ->select('p')
            ->from('MartonPortfolioBundle:Photography', 'p')
            ->orderBy("p.order", "DESC");

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
    }

}