<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 5/26/16
 * Time: 3:42 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="inspiraciones_users_gusta")
 */
class InspiracionUserGusta
{
    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="User")
     **/
    private $user;

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="Inspiracion")
     **/
    private $inspiracion;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

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

    public function __construct() {
        $this->updatedAt = new \DateTime('now');
    }
}