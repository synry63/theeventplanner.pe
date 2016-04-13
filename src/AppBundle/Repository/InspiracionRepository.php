<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/8/16
 * Time: 1:13 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class InspiracionRepository extends EntityRepository
{
    /**
     * @param int $limit
     * @return mixed
     */
    public function getBestInspiraciones($limit = 5){

        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();
        $qb->select('i as inspiracion,avg(ci.nota) as mymoy')
            ->from('AppBundle\Entity\Inspiracion', 'i')
            ->join('i.comentariosInspiracion','ci')
            ->setMaxResults( $limit );
        $qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('i');
        $query = $qb->getQuery();

        return $query->getResult();
    }
    public function getAll($type){
        $qb = $this->createQueryBuilder('i')
            ->where('i.type = :type')
            ->setParameter('type', $type);
        $query = $qb->getQuery();

        return $query;
    }
}