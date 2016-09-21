<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/8/16
 * Time: 1:13 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class NoticiaRepository extends EntityRepository
{
    public function getNoticiasWithCountComments($type,$limit = NULL){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n as noticia,COUNT(cn) as total')
            ->from('AppBundle\Entity\Noticia', 'n')
            ->leftJoin('n.comentarios','cn')
            ->where('n.type = :type')
            ->setParameters(array(
                'type' => $type,
            ));
        if($limit!=NULL)
            $qb->setMaxResults($limit);
        $qb->addGroupBy('n');
        $qb->addOrderBy('n.adedAt', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }
    public function getNoticiasWithCountCommentsDiff($type,$id){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n as noticia,COUNT(cn) as total')
            ->from('AppBundle\Entity\Noticia', 'n')
            ->leftJoin('n.comentarios','cn')
            ->where('n.type = :type')
            ->andWhere('n.id != :id')
            ->setParameters(array(
                'type' => $type,
                'id' =>$id
            ));

        $qb->addGroupBy('n');
        $qb->addOrderBy('n.adedAt', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}