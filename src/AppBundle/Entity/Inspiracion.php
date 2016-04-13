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
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $img_small;

    /**
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $img_large;

    /**
     * @ORM\Column(type="text",nullable=true,)
     */
    private $description;

    /**
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $music_path;

    /**
     * @ORM\Column(type="string",nullable=true, length=100)
     */
    private $video_path;

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
     * @param mixed $music_path
     */
    public function setMusicPath($music_path)
    {
        $this->music_path = $music_path;
    }

    /**
     * @return mixed
     */
    public function getMusicPath()
    {
        return $this->music_path;
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
     * @param mixed $video_path
     */
    public function setVideoPath($video_path)
    {
        $this->video_path = $video_path;
    }

    /**
     * @return mixed
     */
    public function getVideoPath()
    {
        return $this->video_path;
    }


}