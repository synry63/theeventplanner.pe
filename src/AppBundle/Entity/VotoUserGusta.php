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
 * @ORM\Table(name="votos_users_gusta")
 */
class VotoUserGusta
{
    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="User")
     **/
    private $user;

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="Voto")
     **/
    private $voto;

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
     * @param mixed $voto
     */
    public function setVoto($voto)
    {
        $this->voto = $voto;
    }

    /**
     * @return mixed
     */
    public function getVoto()
    {
        return $this->voto;
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