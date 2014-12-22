<?php
/**
 * Created by PhpStorm.
 * User: Marci
 * Date: 2014.09.02.
 * Time: 14:55
 */

namespace Marton\PortfolioBundle\Repository;


use Doctrine\ORM\EntityRepository;

class DrawingRepository extends EntityRepository {

    public function findAllDrawings(){

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb ->select('d')
            ->from('MartonPortfolioBundle:Drawing', 'd')
            ->orderBy("d.year", "DESC");

        $query = $qb->getQuery();
        $result = $query->getResult();

        return $result;
    }

} 