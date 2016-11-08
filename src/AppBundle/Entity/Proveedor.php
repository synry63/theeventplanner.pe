<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/1/16
 * Time: 1:43 PM
 */
// src/AppBundle/Entity/Product.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProveedorRepository")
 * @Vich\Uploadable
 * @ORM\Table(name="proveedores")
 * @UniqueEntity(fields="email", message="El correo electrónico fue tomada")
 * @UniqueEntity(fields="username", message="El usuario ya fue tomado")
 */
class Proveedor implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=25, unique=true)
     * @Assert\Regex(
     *     pattern="/\040/",
     *     match=false,
     *     message="El usuario no puede contener espacios en blanco.")
     *
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     *
     */
    private $password;

    /**
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     *
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=64)
     *
     */
    private $telefono;

    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $banner;
    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $web;

    /**
     * @ORM\Column(type="boolean",nullable=true,options={"default": false})
     */
    private $isAccepted;

    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $facebookLink;
    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $twitterLink;
    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $pinteresLink;
    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $instagramLink;
    /**
     * @ORM\Column(type="string",nullable=true, length=255)
     *
     */
    private $direccion;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     */
    private $countCotizar;

    /**
     * @ORM\Column(type="integer",nullable=true)
     *
     */
    private $countVerTelefono;

    /**
     * @ORM\Column(type="string", length=64)
     *
     */
    private $departamento;

    /**
     * @ORM\Column(type="string", length=64)
     *
     */
    private $distrito;



    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $registeredAt;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * @var File
     */
    private $tempFile;

    /**
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function getTempFile()
    {
        return $this->tempFile;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\File $tempFile
     */
    public function setTempFile($tempFile)
    {
        $this->tempFile = $tempFile;
    }

    /**
     * @param mixed $facebookLink
     */
    public function setFacebookLink($facebookLink)
    {
        $this->facebookLink = $facebookLink;
    }

    /**
     * @return mixed
     */
    public function getCountCotizar()
    {
        return $this->countCotizar;
    }

    /**
     * @param mixed $countCotizar
     */
    public function setCountCotizar($countCotizar)
    {
        $this->countCotizar = $countCotizar;
    }

    /**
     * @return mixed
     */
    public function getCountVerTelefono()
    {
        return $this->countVerTelefono;
    }

    /**
     * @param mixed $countVerTelefono
     */
    public function setCountVerTelefono($countVerTelefono)
    {
        $this->countVerTelefono = $countVerTelefono;
    }



    /**
     * @return mixed
     */
    public function getFacebookLink()
    {
        return $this->facebookLink;
    }

    /**
     * @param mixed $instagramLink
     */
    public function setInstagramLink($instagramLink)
    {
        $this->instagramLink = $instagramLink;
    }

    /**
     * @return mixed
     */
    public function getInstagramLink()
    {
        return $this->instagramLink;
    }

    /**
     * @param mixed $pinteresLink
     */
    public function setPinteresLink($pinteresLink)
    {
        $this->pinteresLink = $pinteresLink;
    }

    /**
     * @return mixed
     */
    public function getPinteresLink()
    {
        return $this->pinteresLink;
    }

    /**
     * @param mixed $twitterLink
     */
    public function setTwitterLink($twitterLink)
    {
        $this->twitterLink = $twitterLink;
    }

    /**
     * @return mixed
     */
    public function getTwitterLink()
    {
        return $this->twitterLink;
    }



    /**
     * @ORM\Column(type="text",nullable=true)
     *
     */
    private $description;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $googleMapLat;

    /**
     * @ORM\Column(type="float",nullable=true)
     */
    private $googleMapLng;

    /**
     * @ORM\Column(type="string",nullable=true, length=255)
     *
     */
    private $googleMapFormatedAddress;
    /**
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="UserProveedorGusta", mappedBy="proveedor",cascade={"persist","remove"})
     **/
    private $usersGusta;

    /**
     * @ORM\OneToMany(targetEntity="Producto", mappedBy="proveedor",cascade={"persist","remove"})
     **/
    private $productos;

    /**
     * @ORM\OneToMany(targetEntity="Foto", mappedBy="proveedor",cascade={"persist","remove"})
     **/
    private $fotos;

    /**
     * @ORM\OneToMany(targetEntity="PreguntaFrequente", mappedBy="proveedor",cascade={"persist","remove"})
     **/
    private $preguntas;

    /**
     * @param mixed $preguntas
     */
    public function setPreguntas($preguntas)
    {
        $this->preguntas = $preguntas;
    }
    /**
     * @param mixed $preguntas
     */
    public function addPreguntas($pregunta)
    {
        $this->preguntas[] = $pregunta;
    }
    /**
     * @return mixed
     */
    public function getPreguntas()
    {
        return $this->preguntas;
    }

    /**
     * @return mixed
     */
    public function getGoogleMapFormatedAddress()
    {
        return $this->googleMapFormatedAddress;
    }

    /**
     * @param mixed $googleMapFormatedAddress
     */
    public function setGoogleMapFormatedAddress($googleMapFormatedAddress)
    {
        $this->googleMapFormatedAddress = $googleMapFormatedAddress;
    }


    /**
     * @param mixed $googleMapLat
     */
    public function setGoogleMapLat($googleMapLat)
    {
        $this->googleMapLat = $googleMapLat;
    }

    /**
     * @return mixed
     */
    public function getGoogleMapLat()
    {
        return $this->googleMapLat;
    }

    /**
     * @param mixed $googleMapLng
     */
    public function setGoogleMapLng($googleMapLng)
    {
        $this->googleMapLng = $googleMapLng;
    }

    /**
     * @return mixed
     */
    public function getGoogleMapLng()
    {
        return $this->googleMapLng;
    }



    /**
     * @ORM\ManyToMany(targetEntity="CategoriaListado", inversedBy="proveedores",cascade={"detach"})
     * @ORM\JoinTable(name="proveedores_categorias_listado")
     *
     */
    private $categoriasListado;

    /**
     * @ORM\OneToMany(targetEntity="ComentarioProveedor", mappedBy="proveedor",cascade={"remove"})
     */
    private $comentariosProveedor;
    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;



    /**
     * @return mixed
     */
    public function getBanner()
    {
        return $this->banner;
    }

    /**
     * @param mixed $departamento
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;
    }

    /**
     * @return mixed
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * @param mixed $distrito
     */
    public function setDistrito($distrito)
    {
        $this->distrito = $distrito;
    }

    /**
     * @return mixed
     */
    public function getDistrito()
    {
        return $this->distrito;
    }


    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }


    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }


    public function __construct() {
        $this->isActive = true;
        $this->isAccepted = false;
        $this->registeredAt = new \DateTime('now');
        $this->comentariosProveedor = new ArrayCollection();
        $this->productos = new ArrayCollection();
        $this->categoriasListado = new ArrayCollection();
        $this->preguntas = new ArrayCollection();



    }

    /**
     * @ORM\OneToOne(targetEntity="Logo",mappedBy="proveedor",cascade={"persist","remove"})
     */
    private $logo;

    /**
     * @ORM\OneToOne(targetEntity="FotoListado",mappedBy="proveedor",cascade={"persist","remove"})
     */
    private $fotoListado;

    /**
     * @param mixed $fotoListado
     */
    public function setFotoListado($fotoListado)
    {
        $this->fotoListado = $fotoListado;
    }

    /**
     * @return mixed
     */
    public function getFotoListado()
    {
        return $this->fotoListado;
    }



    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
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
     * @param mixed $categoriasListado
     */
    public function setCategoriasListado($categoriasListado)
    {
        $this->categoriasListado = $categoriasListado;
    }

    /**
     * @return mixed
     */
    public function getCategoriasListado()
    {
        return $this->categoriasListado;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $direccion
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    /**
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
       return $this->email;
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


    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $productos
     */
    public function setProductos($productos)
    {
        $this->productos = $productos;
    }

    /**
     * @return mixed
     */
    public function getProductos()
    {
        return $this->productos;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
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
     * @param mixed $web
     */
    public function setWeb($web)
    {
        $this->web = $web;
    }

    /**
     * @return mixed
     */
    public function getWeb()
    {
        return $this->web;
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
    public function getUsersGusta()
    {
        return $this->usersGusta;
    }

    /**
     * @param mixed $usersGusta
     */
    public function setUsersGusta($usersGusta)
    {
        $this->usersGusta = $usersGusta;
    }

    /**
     * @return mixed
     */
    public function getFotos()
    {
        return $this->fotos;
    }
    public function check_categorias_with($cates_total){
        $this->code = array();
        //var_dump($cates_total[0]->getNombre());
        foreach ($cates_total as $key=>$cate){
            $this->code[] = $cate->getNombre();
            //$this->code[] = $cate;
        }
        //var_dump($this->code);

    }
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }
    public function getRoles()
    {
        return array('ROLE_USER');
    }
    public function eraseCredentials()
    {

    }
    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        //var_dump($this->categoriasListado);
        if(count($this->categoriasListado)==0){
            $context->buildViolation('Selecione un listado como minimo')
                ->atPath('categoriasListado')
                ->addViolation();
        }

    }
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @param mixed $isAccepted
     */
    public function setIsAccepted($isAccepted)
    {
        $this->isAccepted = $isAccepted;
    }

    /**
     * @return mixed
     */
    public function getIsAccepted()
    {
        return $this->isAccepted;
    }



    /**
     * @param \DateTime $registeredAt
     */
    public function setRegisteredAt($registeredAt)
    {
        $this->registeredAt = $registeredAt;
    }

    /**
     * @return \DateTime
     */
    public function getRegisteredAt()
    {
        return $this->registeredAt;
    }


    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->registeredAt
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->registeredAt
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
    }

}