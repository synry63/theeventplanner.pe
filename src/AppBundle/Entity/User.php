<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/7/16
 * Time: 1:50 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuarios")
 */
class User
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="ComentarioProveedor", mappedBy="user")
     */
    private $comentarios_proveedor;

    /**
     * @param mixed $comentarios_proveedor
     */
    public function setComentariosProveedor($comentarios_proveedor)
    {
        $this->comentarios_proveedor = $comentarios_proveedor;
    }

    /**
     * @return mixed
     */
    public function getComentariosProveedor()
    {
        return $this->comentarios_proveedor;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }



    public function __construct() {
        $this->comentarios_proveedor = new ArrayCollection();
    }

}