<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/15/16
 * Time: 11:17 AM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fotos_users_gusta")
 */
class FotoUserGusta
{
    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="User")
     **/
    private $user;

    /**
     * @ORM\Id @ORM\ManyToOne(targetEntity="Foto")
     **/
    private $foto;

    /**
     * @param mixed $foto
     */
    public function setFoto($foto)
    {
        $this->foto = $foto;
    }

    /**
     * @return mixed
     */
    public function getFoto()
    {
        return $this->foto;
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


}