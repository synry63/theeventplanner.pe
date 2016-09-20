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
use Doctrine\ORM\Mapping\AttributeOverrides;
use Doctrine\ORM\Mapping\AttributeOverride;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="usuarios")
 * @AttributeOverrides({
 *     @AttributeOverride(name="password",
 *         column=@ORM\Column(
 *             name="password",
 *             type="string",
 *             length=255,
 *             nullable=true
 *         )
 *     )
 * })
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
     * @ORM\Column(type="string",nullable=true, length=8)
     *
     *
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
     * @ORM\Column(type="string",nullable=true, length=10)
     *
    */
    protected $telefono;

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
     * @ORM\OneToOne(targetEntity="FotoProfile",mappedBy="user",cascade={"persist"})
     */
    private $profile;

    /**
     * @ORM\Column(type="string",nullable=true, length=255)
     */
    protected $facebook_id;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;

    /**
     * @return mixed
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * @param mixed $facebook_id
     */
    public function setFacebookId($facebook_id)
    {
        $this->facebook_id = $facebook_id;
    }

    /**
     * @return mixed
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * @param mixed $facebook_access_token
     */
    public function setFacebookAccessToken($facebook_access_token)
    {
        $this->facebook_access_token = $facebook_access_token;
    }


    /**
     * @param mixed $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @param mixed $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }


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