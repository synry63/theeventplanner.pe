<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/7/16
 * Time: 1:08 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InspiracionRepository")
 * @Vich\Uploadable
 * @ORM\Table(name="inspiraciones")
 */
class Inspiracion
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $img;
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
    private $adedAt;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="inspiracion_foto", fileNameProperty="img")
     * @var File
     */
    private $imgFile;

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
     * @ORM\OneToMany(targetEntity="ComentarioInspiracion", mappedBy="inspiracion",cascade={"remove"})
     */
    private $comentariosInspiracion;

    /**
     * @ORM\OneToMany(targetEntity="InspiracionUserGusta", mappedBy="inspiracion",cascade={"detach"})
     */
    private $users;

    /**
    @ORM\ManyToOne(targetEntity="Tendencia",inversedBy="inspiraciones")
    @ORM\JoinColumn(name="tendencia_id", referencedColumnName="id")
     **/
    private $tendencia;

    /**
     * @param mixed $tendencia
     */
    public function setTendencia($tendencia)
    {
        $this->tendencia = $tendencia;
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
     * @return mixed
     */
    public function getTendencia()
    {
        return $this->tendencia;
    }

    /**
     * @param mixed $comentarios_inspiracion
     */
    public function setComentariosInspiracion($comentarios_inspiracion)
    {
        $this->comentariosInspiracion = $comentarios_inspiracion;
    }

    /**
     * @return mixed
     */
    public function getComentariosInspiracion()
    {
        return $this->comentariosInspiracion;
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
     * @param mixed $imgLarge
     */
    public function setImg($imgLarge)
    {
        $this->img = $imgLarge;
    }

    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
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
     * @param mixed $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
    function __construct() {
        $this->adedAt = new \DateTime('now');
    }

}