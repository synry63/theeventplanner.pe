<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/6/16
 * Time: 11:34 AM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoriaListadoRepository")
 * @ORM\Table(name="categorias_listado")
 */
class CategoriaListado
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
     * @ORM\Column(type="string", length=100)
     */
    private $slug;
    /**
     * @ORM\ManyToMany(targetEntity="Proveedor", mappedBy="categoriasListado")
     */

    private $proveedores;

    /**
     * @ORM\ManyToOne(targetEntity="CategoriaListado", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="CategoriaListado", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $img_small;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $img_large;

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {


        return $this->children;
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
     * @param mixed $img_large
     */
    public function setImgLarge($img_large)
    {
        $this->img_large = $img_large;
    }

    /**
     * @return mixed
     */
    public function getImgLarge()
    {
        return $this->img_large;
    }

    /**
     * @param mixed $img_small
     */
    public function setImgSmall($img_small)
    {
        $this->img_small = $img_small;
    }

    /**
     * @return mixed
     */
    public function getImgSmall()
    {
        return $this->img_small;
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
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $proveedores
     */
    public function setProveedores($proveedores)
    {
        $this->proveedores = $proveedores;
    }

    /**
     * @return mixed
     */
    public function getProveedores()
    {
        return $this->proveedores;
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


    public function __construct() {
        $this->proveedores = new ArrayCollection();
        $this->children = new ArrayCollection();
    }


}