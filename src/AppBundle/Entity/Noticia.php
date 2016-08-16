<?php
/**
 * Created by PhpStorm.
 * User: pmary-game
 * Date: 8/16/16
 * Time: 2:04 PM
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NoticiaRepository")
 * @ORM\Table(name="noticias")
 */
class Noticia
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $nombre;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;


    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $adedAt;

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
    public function __construct() {
        $this->adedAt = new \DateTime('now');
    }
}
