<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/27/16
 * Time: 7:35 AM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="preguntas_frequentes")
 */
class PreguntaFrequente
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="string")
     */
    private $pregunta;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $respuesta;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
    @ORM\ManyToOne(targetEntity="Proveedor",inversedBy="preguntas")
    @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     **/
    private $proveedor;

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



    /**
     * @param mixed $pregunta
     */
    public function setPregunta($pregunta)
    {
        $this->pregunta = $pregunta;
    }

    /**
     * @return mixed
     */
    public function getPregunta()
    {
        return $this->pregunta;
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



    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }


    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    public function __construct() {
        $this->updatedAt = new \DateTime('now');
    }

}