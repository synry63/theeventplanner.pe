<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/8/16
 * Time: 1:13 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class ProveedorRepository extends EntityRepository
{
    /**
     * @param int $limit
     * @return mixed
     */
    public function getBestProveedores($slug_parent_category,$limit = 5){

        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder();
        $qb->select('p as proveedor,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Proveedor', 'p')
            ->join('p.comentariosProveedor','cp')
            ->join('p.categoriasListado','cl')
            ->where('cl.slug = :slug')
            ->setMaxResults( $limit )
            ->setParameter('slug', $slug_parent_category);
        $qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('p');
        $query = $qb->getQuery();

        return $query->getResult();
    }

    /**
     * @param $cate
     * @return mixed
     */
    public function getProveedoresByCategory($cate){

        $qb = $this->createQueryBuilder('p')
            ->join('p.categoriasListado','c')
            ->where('c = :cate')
            ->setParameter('cate', $cate);
        $query = $qb->getQuery();

        return $query;
    }
}