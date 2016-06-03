<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/7/16
 * Time: 1:50 PM
 */
namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="usuarios")
 */
class User extends BaseUser
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected  $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Ingrese sus Nombres", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=64,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $nombres;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(message="Ingrese sus Apellidos", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=3,
     *     max=64,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $apellidos;

    /**
     * @ORM\Column(type="string", length=8)
     *
     * @Assert\NotBlank(message="Ingrese su DNI", groups={"Registration", "Profile"})
     * @Assert\Length(
     *     min=8,
     *     max=8,
     *     minMessage="Must be exactly 8.",
     *     maxMessage="Must be exactly 8.",
     *     groups={"Registration", "Profile"}
     * )
     */
    protected $dni;

    /**
     * @ORM\OneToMany(targetEntity="ComentarioProveedor", mappedBy="user")
     */
    private $comentariosProveedor;

    /**
     * @ORM\OneToMany(targetEntity="ComentarioInspiracion", mappedBy="user")
     */
    private $comentariosInspiracion;

    /**
     * @ORM\OneToMany(targetEntity="InspiracionUserGusta", mappedBy="user")
     */
    private $inspiraciones;
    /**
     * @ORM\OneToMany(targetEntity="FotoUserGusta", mappedBy="user")
     */
    private $fotos;

    /**
     * @ORM\OneToMany(targetEntity="VotoUserGusta", mappedBy="user")
     */
    private $votos;

    /**
     * @ORM\OneToMany(targetEntity="TendenciaUserGusta", mappedBy="user")
     */
    private $tendencias;

    /**
     * @param mixed $votos
     */
    public function setVotos($votos)
    {
        $this->votos = $votos;
    }

    /**
     * @return mixed
     */
    public function getVotos()
    {
        return $this->votos;
    }

    /**
     * @param mixed $tendencias
     */
    public function setTendencias($tendencias)
    {
        $this->tendencias = $tendencias;
    }

    /**
     * @return mixed
     */
    public function getTendencias()
    {
        return $this->tendencias;
    }


    /**
     * @param mixed $fotos
     */
    public function setFotos($fotos)
    {
        $this->fotos = $fotos;
    }

    /**
     * @return mixed
     */
    public function getFotos()
    {
        return $this->fotos;
    }



    /**
     * @param mixed $dni
     */
    public function setDni($dni)
    {
        $this->dni = $dni;
    }

    /**
     * @return mixed
     */
    public function getDni()
    {
        return $this->dni;
    }



    /**
     * @param mixed $apellidos
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    /**
     * @return mixed
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }


    /**
     * @param mixed $comentariosInspiracion
     */
    public function setComentariosInspiracion($comentariosInspiracion)
    {
        $this->comentariosInspiracion = $comentariosInspiracion;
    }

    /**
     * @return mixed
     */
    public function getComentariosInspiracion()
    {
        return $this->comentariosInspiracion;
    }



    /**
     * @param mixed $comentarios_proveedor
     */
    public function setComentariosProveedor($comentarios_proveedor)
    {
        $this->comentariosProveedor = $comentarios_proveedor;
    }

    /**
     * @return mixed
     */
    public function getComentariosProveedor()
    {
        return $this->comentariosProveedor;
    }

    /**
     * @param mixed $nombres
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;
    }

    /**
     * @return mixed
     */
    public function getNombres()
    {
        return $this->nombres;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $inspiraciones
     */
    public function setInspiraciones($inspiraciones)
    {
        $this->inspiraciones = $inspiraciones;
    }

    /**
     * @return mixed
     */
    public function getInspiraciones()
    {
        return $this->inspiraciones;
    }


    public function __construct() {
        parent::__construct();
        $this->comentariosProveedor = new ArrayCollection();
        $this->comentariosInspiracion = new ArrayCollection();
        $this->fotos = new ArrayCollection();
    }

}