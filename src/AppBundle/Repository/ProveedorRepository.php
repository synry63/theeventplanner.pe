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
    public function getBestProveedores($parent_category,$limit = 5){

        $em = $this->getEntityManager();



        $qb = $em->createQueryBuilder();
        $qb->select('p as proveedor,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Proveedor', 'p')
            ->join('p.comentariosProveedor','cp')
            ->join('p.categoriasListado','cl')
            ->where('cl.parent = :cate')
            ->setMaxResults( $limit )
            ->setParameter('cate', $parent_category);
        $qb->addOrderBy('mymoy', 'DESC');
        $qb->addGroupBy('p');
        $query = $qb->getQuery();

        return $query->getResult();
    }
    public function getProveedorRating($proveedor){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Proveedor', 'p')
            ->join('p.comentariosProveedor','cp')
            ->where('p = :proveedor')
            ->setParameter('proveedor', $proveedor);
        $qb->addGroupBy('p');
        $query = $qb->getQuery();
        $result = $query->getOneOrNullResult();
        if($result!=null) $result = $result['mymoy'];
        return $result;
    }
    public function getProveedorWithRating($proveedor_slug){
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('p as proveedor,avg(cp.nota) as mymoy')
            ->from('AppBundle\Entity\Proveedor', 'p')
            ->join('p.comentariosProveedor','cp')
            ->where('p.slug = :slug')
            ->setParameter('slug', $proveedor_slug);
        $qb->addGroupBy('p');
        $query = $qb->getQuery();

        return $query->getResult();
    }
    public function getProveedoresOrderRecent(){
        $qb = $this->createQueryBuilder('p')
            ->orderBy('p.registeredAt', 'DESC');

        $query = $qb->getQuery();

        return $query;
    }
    /**
     * @param $cate
     * @return mixed
     */
    public function getProveedoresByCategory($cate){


        $qb = $this->createQueryBuilder('p')
            ->join('p.categoriasListado','cl')
            ->where('cl = :cate')
            ->andWhere('p.isActive = :state')
            ->setParameters(array(
                'cate' => $cate,
                'state'=>true
            ));
        $query = $qb->getQuery();


        return $query;
    }
}