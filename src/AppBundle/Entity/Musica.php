<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 4/7/16
 * Time: 1:08 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="musicas")
 */
class Musica
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string",unique=true)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $embedUrl;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $shareUrl;



    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value = 0)
     */
    private $sort;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @param mixed $sort
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
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
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
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
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @return mixed
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @return mixed
     */
    public function getEmbedUrl()
    {
        return $this->embedUrl;
    }

    /**
     * @param mixed $embedUrl
     */
    public function setEmbedUrl($embedUrl)
    {
        $this->embedUrl = $embedUrl;
    }

    /**
     * @return mixed
     */
    public function getShareUrl()
    {
        return $this->shareUrl;
    }

    /**
     * @param mixed $shareUrl
     */
    public function setShareUrl($shareUrl)
    {
        $this->shareUrl = $shareUrl;
    }


}