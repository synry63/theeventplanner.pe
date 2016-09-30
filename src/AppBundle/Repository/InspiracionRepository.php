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
    public function getLastInspiraciones($type,$limit = 5){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('i as inspiracion')
            ->from('AppBundle\Entity\Inspiracion', 'i')
            ->join('i.tendencia','ti')
            ->where('ti.type = :type')
            ->setParameter('type', $type)
            ->setMaxResults( $limit );
        $qb->addOrderBy('i.updatedAt', 'DESC');

        $query = $qb->getQuery();
        return $query->getResult();
    }
    public function getBestInspiraciones($type,$limit = 5){

        $em = $this->getEntityManager();

        /*$qb = $em->createQueryBuilder();
        $qb->select('i as inspiracion,avg(ci.nota) as mymoy')
            ->from('AppBundle\Entity\Inspiracion', 'i')
            ->join('i.comentariosInspiracion','ci')
            ->setMaxResults( $limit );
        $qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('i');*/

        $qb = $em->createQueryBuilder();
        $qb->select('i as inspiracion,COUNT(ui) as total')
            ->from('AppBundle\Entity\Inspiracion', 'i')
             ->join('i.users','ui')
             ->join('i.tendencia','ti')
            ->where('ti.type = :type')
            ->setParameter('type', $type)
            ->setMaxResults( $limit );
        //$qb->addGroupBy('ui.inspiracion');

        $qb->addGroupBy('i');
        $qb->addOrderBy('total', 'DESC');
        $qb->addOrderBy('i.adedAt', 'DESC');
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