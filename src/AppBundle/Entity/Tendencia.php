<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/27/16
 * Time: 7:35 AM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 * @ORM\Table(name="tendencias")
 * @UniqueEntity(fields="nombre", message="Nombre already taken")
 */
class Tendencia
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string",unique=true)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $type;
    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;


    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     */
    private $sort;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;
    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="tendencia_foto", fileNameProperty="img")
     * @var File
     */
    private $imgFile;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $img;

    /**
     * @ORM\OneToMany(targetEntity="Inspiracion", mappedBy="tendencia",cascade={"remove"})
     **/
    private $inspiraciones;

    /**
     * @Assert\Valid
     * @ORM\OneToMany(targetEntity="Source", mappedBy="tendencia",cascade={"persist","remove"})
     **/
    private $sources;
    /**
     * @Assert\Valid
     * @ORM\OneToMany(targetEntity="LinkCategoria", mappedBy="tendencia",cascade={"persist","remove"})
     **/
    private $linksCategorias;

    /**
     * @return mixed
     */
    public function getLinksCategorias()
    {
        return $this->linksCategorias;
    }

    /**
     * @param mixed $linksCategorias
     */
    public function setLinksCategorias($linksCategorias)
    {
        $this->linksCategorias = $linksCategorias;
    }


    public function removeSource(Source $s)
    {
        $this->sources->removeElement($s);
    }


    /**
     * @param mixed $sources
     */
    public function setSources($sources)
    {
        $this->sources = $sources;
    }

    /**
     * @return mixed
     */
    public function getSources()
    {
        return $this->sources;
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


    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
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
    public function setImgFile(File $image = null)
    {
        $this->imgFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getImgFile()
    {
        return $this->imgFile;
    }
    /**
     * @param mixed $img
     */
    public function setImg($img)
    {
        $this->img = $img;
    }

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
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
     * @param mixed $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
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
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public function __construct()
    {
        $this->sources = new ArrayCollection();
    }
}