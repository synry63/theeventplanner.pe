<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/8/16
 * Time: 1:13 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class UserRepository extends EntityRepository
{
    public function getUserComments(){

    }
    public function getProveedoresFavotiros($u,$p_category){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('up')
            ->from('AppBundle\Entity\UserProveedorGusta', 'up')
            ->join('up.proveedor','p')
            ->join('p.categoriasListado','cl')
            ->where('cl.parent = :cate')
            ->andWhere('up.user = :user')
            ->setParameters(array(
                'user' => $u,
                'cate'=>$p_category
            ));
        //$qb->addGroupBy('up');
        //$qb->addGroupBy('p');
        $query = $qb->getQuery();
        $r = $query->getResult();

        return $r;
    }

}