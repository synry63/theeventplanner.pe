<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 6/1/16
 * Time: 4:44 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="tendencias_users_gusta")
 */
class TendenciaUserGusta
{
    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="User")
     **/
    private $user;

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="Tendencia")
     **/
    private $tendencia;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

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
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }
    public function __construct() {
        $this->updatedAt = new \DateTime('now');
    }

}