<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/7/16
 * Time: 1:08 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InspiracionRepository")
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
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $slug;

    /**
     * @ORM\Column(type="string",nullable=true, length=64)
     */
    private $type;

    /**
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $nombre;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $addedAt;
    /**
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $imgLarge;

    /**
     * @ORM\Column(type="text",nullable=true,)
     */
    private $description;

    /**
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $musicPath;

    /**
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $videoPath;

    /**
     * @ORM\OneToMany(targetEntity="ComentarioInspiracion", mappedBy="inspiracion")
     */
    private $comentariosInspiracion;

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
    public function setImgLarge($imgLarge)
    {
        $this->imgLarge = $imgLarge;
    }

    /**
     * @return mixed
     */
    public function getImgLarge()
    {
        return $this->imgLarge;
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
     * @param mixed $musicPath
     */
    public function setMusicPath($musicPath)
    {
        $this->musicPath = $musicPath;
    }

    /**
     * @return mixed
     */
    public function getMusicPath()
    {
        return $this->musicPath;
    }

    /**
     * @param mixed $videoPath
     */
    public function setVideoPath($videoPath)
    {
        $this->videoPath = $videoPath;
    }

    /**
     * @return mixed
     */
    public function getVideoPath()
    {
        return $this->videoPath;
    }




}