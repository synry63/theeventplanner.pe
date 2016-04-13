<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/8/16
 * Time: 1:57 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="comentarios_inspiraciones")
 */
class ComentarioInspiracion
{

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="User")
     **/
    private $user;

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="Inspiracion")
     **/
    private $inspiracion;


    /** @ORM\Column(type="integer") **/
    private $nota;

    /**
     * @ORM\Column(type="text")
     */
    private $comentario;

    /**
     * @param mixed $inspiracion
     */
    public function setInspiracion($inspiracion)
    {
        $this->inspiracion = $inspiracion;
    }

    /**
     * @return mixed
     */
    public function getInspiracion()
    {
        return $this->inspiracion;
    }



    /**
     * @param mixed $comentario
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;
    }

    /**
     * @return mixed
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * @param mixed $nota
     */
    public function setNota($nota)
    {
        $this->nota = $nota;
    }

    /**
     * @return mixed
     */
    public function getNota()
    {
        return $this->nota;
    }
}