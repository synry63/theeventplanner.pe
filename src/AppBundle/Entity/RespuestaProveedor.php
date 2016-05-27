<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/27/16
 * Time: 6:23 AM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="respuestas_proveedores")
 */
class RespuestaProveedor
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="ComentarioProveedor", inversedBy="respuesta")
     * @ORM\JoinColumn(name="comentario_proveedor_id", referencedColumnName="id")
     */
    private $comentarioProveedor;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $respuesta;
    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $adedAt;

    /**
     * @param \DateTime $adedAt
     */
    public function setAdedAt($adedAt)
    {
        $this->adedAt = $adedAt;
    }

    /**
     * @return \DateTime
     */
    public function getAdedAt()
    {
        return $this->adedAt;
    }

    /**
     * @param mixed $comentarioProveedor
     */
    public function setComentarioProveedor($comentarioProveedor)
    {
        $this->comentarioProveedor = $comentarioProveedor;
    }

    /**
     * @return mixed
     */
    public function getComentarioProveedor()
    {
        return $this->comentarioProveedor;
    }

    /**
     * @param mixed $respuesta
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;
    }

    /**
     * @return mixed
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    public function __construct() {
        $this->adedAt = new \DateTime('now');
    }

}