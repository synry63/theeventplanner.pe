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
     * @ORM\Column(type="string")
     */
    private $slug;

    /**
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * @ORM\Column(type="string")
     */
    private $nombre;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $img;
    /**
     * @ORM\Column(type="integer")
     */
    private $order;
    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @param mixed $order
     */
    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }
    /**
     * @ORM\OneToMany(targetEntity="ComentarioInspiracion", mappedBy="inspiracion")
     */
    private $comentariosInspiracion;

    /**
     * @ORM\OneToMany(targetEntity="InspiracionUserGusta", mappedBy="inspiracion")
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




}