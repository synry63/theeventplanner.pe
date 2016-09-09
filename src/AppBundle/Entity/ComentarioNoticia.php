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
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ComentarioNoticiaRepository")
 * @ORM\Table(name="comentarios_noticias")
 */
class ComentarioNoticia
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="User")
     **/
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Noticia")
     **/
    private $noticia;

    /**
     * @ORM\Column(type="text")
     */
    private $comentario;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $adedAt;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getNoticia()
    {
        return $this->noticia;
    }

    /**
     * @param mixed $noticia
     */
    public function setNoticia($noticia)
    {
        $this->noticia = $noticia;
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

}