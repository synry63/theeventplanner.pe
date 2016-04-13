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

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProveedorRepository")
 * @ORM\Table(name="proveedores")
 */
class Proveedor
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $telefono;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $logo;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $banner;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $web;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $email;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $direccion;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $googlemap;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Producto", mappedBy="proveedor")
     **/
    private $productos;

    /**
     * @ORM\ManyToMany(targetEntity="CategoriaListado", inversedBy="proveedores")
     * @ORM\JoinTable(name="proveedores_categorias_listado")
     */
    private $categoriasListado;

    /**
     * @ORM\OneToMany(targetEntity="ComentarioProveedor", mappedBy="proveedor")
     */
    private $comentariosProveedor;




    public function __construct() {
        $this->comentariosProveedor = new ArrayCollection();
        $this->productos = new ArrayCollection();
        $this->categoriasListado = new ArrayCollection();
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
     * @param mixed $googlemap
     */
    public function setGooglemap($googlemap)
    {
        $this->googlemap = $googlemap;
    }

    /**
     * @return mixed
     */
    public function getGooglemap()
    {
        return $this->googlemap;
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




}