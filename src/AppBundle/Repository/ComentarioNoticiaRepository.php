<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/16/16
 * Time: 4:05 PM
 */
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;


class ComentarioNoticiaRepository extends EntityRepository
{
    public function getAllComments($noticia,$limit = null){

        $result = $this->findBy(array('noticia' => $noticia),
            array('adedAt' => 'DESC'),
            $limit
        );
        return $result;
    }
}