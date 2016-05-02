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
    public function getProveedoresByCategory($main_categoria,$cate){

        $parameters = array(
            'cate' => $cate,
        //    'main_cate' => $main_categoria
        );
        /*$subquery = $this->createQueryBuilder('p')
            ->join('p.categoriasListado','c')
            ->where('c = :main_cate')
            ->setParameter('main_cate', $main_categoria)
            ->getDQL();
*/

        $qb = $this->createQueryBuilder('p')
            ->select('p as proveedor, COUNT(cl) as counter')
            ->join('p.categoriasListado','cl')
            ->where('cl = :cate')
            ->orWhere('cl = :main_cate')
            ->addGroupBy('p')
            ->having('counter >= 2')
            ->setParameters(array(
                'cate' => $cate,
                'main_cate' => $main_categoria,
            ));
        $query = $qb->getQuery();
        /*$data = $query->getResult();
        $out = array();
        foreach ($data as $pro){
            foreach ($pro->getCategoriasListado() as $one_cate){
                if($one_cate->getSlug()==$cate->getSlug()){
                    $out[] = $pro;
                    break;
                }
            }
        }*/

        //var_dump($out);
        //exit;

        return $query;
    }
}