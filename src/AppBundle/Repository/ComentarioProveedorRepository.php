<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/16/16
 * Time: 4:05 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class ComentarioProveedorRepository extends EntityRepository
{
    public function getAllComments($proveedor,$limit = null){

        $result = $this->findBy(array('proveedor' => $proveedor),
                                array('adedAt' => 'DESC'),
                                $limit
        );
        return $result;
    }
}