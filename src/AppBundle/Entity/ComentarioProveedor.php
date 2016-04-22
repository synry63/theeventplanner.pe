<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/7/16
 * Time: 1:49 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ComentarioProveedorRepository")
 * @ORM\Table(name="comentarios_proveedores")
 */
class ComentarioProveedor
{

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="User")
     **/
    private $user;

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="Proveedor",inversedBy="comentariosProveedor")
     **/
    private $proveedor;



    /** @ORM\Column(type="integer")
     *  @Assert\NotBlank()
     **/
    private $nota;

    /**
     * @ORM\Column(type="text")
     */
    private $comentario;

    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $nota
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
    }

    /**
     * @return mixed
     */
    public function getNota()
    {
        return $this->nota;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $proveedor
     */
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;
    }

    /**
     * @return mixed
     */
    public function getProveedor()
    {
        return $this->proveedor;
    }


    public function __construct() {

    }
}