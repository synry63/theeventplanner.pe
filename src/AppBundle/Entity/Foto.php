<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/15/16
 * Time: 11:14 AM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FotoRepository")
 * @ORM\Table(name="fotos")
 */
class Foto
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $img_small;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $img_large;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $img_thumb;

    /**
    @ORM\ManyToOne(targetEntity="Proveedor",inversedBy="fotos")
    @ORM\JoinColumn(name="proveedor_id", referencedColumnName="id")
     **/
    private $proveedor;

    /**
     * @ORM\OneToMany(targetEntity="FotoUserGusta", mappedBy="foto")
     */
    private $users;

    /**
     * @param mixed $proveedor
     */
    public function setProveedor($proveedor)
    {
        $this->proveedor = $proveedor;
    }

    /**
     * @return mixed
     */
    public function getProveedor()
    {
        return $this->proveedor;
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
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
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
     * @param mixed $img_thumb
     */
    public function setImgThumb($img_thumb)
    {
        $this->img_thumb = $img_thumb;
    }

    /**
     * @return mixed
     */
    public function getImgThumb()
    {
        return $this->img_thumb;
    }


}