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

    function getCategoriasChildren($slug){

        $weddingParentCategoria = $this->findOneBy(array('slug' => $slug));

        return $weddingParentCategoria->getChildren();
    }
    /*function getCategoriasStructured(){
        $qb = $this->createQueryBuilder('c')
            ->where('i.type = :type')
            ->setParameter('type', $type);
        $query = $qb->getQuery();
    }*/
}