<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/16/16
 * Time: 10:20 AM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class FotoRepository extends EntityRepository
{
    /**
     * @param $slug
     * @param int $limit
     * @return mixed
     */
    public function getProveedorFotos($proveedor){

        $fotos = $this->findBy(array('proveedor'=>$proveedor));

        return $fotos;

    }
    public function getBestFotosProveedor($proveedor,$limit = 5){
        $qb = $this->createQueryBuilder('f')
            ->select('f as foto, count(fu.foto) as number')
            ->join('f.users','fu')
            ->join('f.proveedor','fp')
            ->where('f.proveedor = :proveedor')
            ->setMaxResults( $limit )
            ->setParameter('proveedor',$proveedor);
        $qb->addGroupBy('foto');
        $qb->addOrderBy('number', 'DESC');

        $query = $qb->getQuery();
        return $query->getResult();
    }
    public function getFotosByCate($cate){

        $qb = $this->createQueryBuilder('f')
            ->join('f.proveedor','fp')
            ->join('fp.categoriasListado','fpc')
            ->where('fpc.parent = :cate')
            ->andWhere('fp.isAccepted = :state')
            ->setParameter('cate',$cate);

        $qb->addOrderBy('f.updatedAt', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }
    public function getBestFotos($parent_category,$limit = 5){
        /*$qb = $this->createQueryBuilder('f')
             ->select('f as foto, count(fu.foto) as number')
             ->join('f.users','fu')
             ->join('f.proveedor','fp')
             ->join('fp.categoriasListado','cl')
             ->where('cl.slug = :slug')
             ->setMaxResults( $limit )
             ->setParameter('slug',$slug);
        $qb->addGroupBy('fu.foto');
        $qb->addOrderBy('number', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();*/

        $qb = $this->createQueryBuilder('f')
            ->select('f as foto, count(fu.foto) as number')
            ->join('f.users','fu')
            ->join('f.proveedor','fp')
            ->join('fp.categoriasListado','cl')
            ->where('cl.parent = :cate')
            ->andWhere('fp.isAccepted = :state')
            ->setMaxResults( $limit )
            ->setParameters(array(
                'cate' => $parent_category,
                'state'=>true
            ));
        $qb->addGroupBy('foto');
        //$qb->addGroupBy('cl');
        $qb->addOrderBy('number', 'DESC');
        $query = $qb->getQuery();
        return $query->getResult();
    }
}