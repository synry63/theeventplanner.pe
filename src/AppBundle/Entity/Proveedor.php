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
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProveedorRepository")
 * @Vich\Uploadable
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
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $nombre;

    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $telefono;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="proveedor_logo", fileNameProperty="logo")
     * @Assert\NotBlank()
     * @var File
     */
    private $logoFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $logo;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $banner;
    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $web;
    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $email;
    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $direccion;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string",nullable=true, length=255)
     */
    private $googlemap;

    /**
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Producto", mappedBy="proveedor")
     **/
    private $productos;

    /**
     * @ORM\OneToMany(targetEntity="Foto", mappedBy="proveedor")
     **/
    private $fotos;

    /**
     * @ORM\ManyToMany(targetEntity="CategoriaListado", inversedBy="proveedores")
     * @ORM\JoinTable(name="proveedores_categorias_listado")
     *
     */
    private $categoriasListado;

    /**
     * @ORM\OneToMany(targetEntity="ComentarioProveedor", mappedBy="proveedor")
     */
    private $comentariosProveedor;

    public $code;


    public function __construct() {
        $this->comentariosProveedor = new ArrayCollection();
        $this->productos = new ArrayCollection();
        $this->categoriasListado = new ArrayCollection();
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setLogoFile(File $image = null)
    {
        $this->logoFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getLogoFile()
    {
        return $this->logoFile;
    }

    /**
     * @param string $imageName
     *
     * @return Product
     */
    public function setLogo($imageName)
    {
        $this->logo = $imageName;

        return $this;
    }

    /**
     * @return string
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
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        //var_dump($this->categoriasListado);
        if(count($this->categoriasListado)==0){
            $context->buildViolation('Can\'t be empty')
                ->atPath('categoriasListado')
                ->addViolation();
        }



    }

}