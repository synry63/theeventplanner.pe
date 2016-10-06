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
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     **/
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Proveedor",inversedBy="comentariosProveedor")
     **/
    private $proveedor;

    /**
     * @ORM\OneToOne(targetEntity="RespuestaProveedor",mappedBy="comentarioProveedor")
     */
    private $respuesta;

    /** @ORM\Column(type="decimal",precision=10,scale=2)
     *  @Assert\NotBlank()
     *  @Assert\GreaterThan(
     *     value = 0
     *  )
     *  @Assert\LessThanOrEqual(
     *     value = 5
     * )
     **/
    private $nota;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 15,
     *      maxMessage = "El titulo no puede tener más de {{ limit }} caracteres"
     * )
     */
    private $titulo;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $comentario;
    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $adedAt;

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

    /**
     * @param mixed $titulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;
    }

    /**
     * @return mixed
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    public function __construct() {
        $this->adedAt = new \DateTime('now');
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


}