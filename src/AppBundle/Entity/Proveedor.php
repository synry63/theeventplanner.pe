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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProveedorRepository")
 * @Vich\Uploadable
 * @ORM\Table(name="proveedores")
 */
class Proveedor implements UserInterface
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
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     *
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="string", length=60, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $telefono;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Assert\NotBlank()
     * @Vich\UploadableField(mapping="proveedor_logo", fileNameProperty="logo")
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
    private $direccion;

    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $departamente;

    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $distrito;




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
     * @return mixed
     */
    public function getDepartamente()
    {
        return $this->departamente;
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

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
        return array('ROLE_PROVEEDOR');
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

}