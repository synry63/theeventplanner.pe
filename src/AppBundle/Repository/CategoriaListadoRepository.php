<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/9/16
 * Time: 7:37 AM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class CategoriaListadoRepository extends EntityRepository
{

    function getCategoriasChildren($slug,$sort = NULL)
    {
        $parentCategoria = $this->findOneBy(array('slug' => $slug));
        $qb = $this->createQueryBuilder('c')
            ->where('c.parent = :cate_parent')
            ->setParameter('cate_parent', $parentCategoria);

        if($sort!=null)
            $qb->addOrderBy('c.nombre', $sort);
        $query = $qb->getQuery();

        return $query->getResult();
    }
    function getCategoriasChildrenManaged($categoria_parent){
        $qb = $this->createQueryBuilder('c')
            ->where('c.parent = :cate_parent')
            ->setParameter('cate_parent', $categoria_parent);

        $qb->addOrderBy('c.nombre', 'ASC');
        $query = $qb->getQuery();

        return $query->getResult();
    }
    /*function getCategoriasStructured(){
        $qb = $this->createQueryBuilder('c')
            ->where('i.type = :type')
            ->setParameter('type', $type);
        $query = $qb->getQuery();
    }*/
}