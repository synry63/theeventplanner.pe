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
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @Vich\Uploadable
 * @ORM\Table(name="parafos")
 */
class Parafo
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $description;

    /**
 * NOTE: This is not a mapped field of entity metadata, just a simple property.
 *
 * @Vich\UploadableField(mapping="blog_foto_parafos", fileNameProperty="img")
 * @var File
 */
    private $imgFile;

    /**
     * @ORM\Column(type="string", length=64,nullable=true)
     */
    private $img;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="blog_foto_parafos", fileNameProperty="img2")
     * @var File
     */
    private $imgFile2;

    /**
     * @ORM\Column(type="string", length=64,nullable=true)
     */
    private $img2;

    /**
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="blog_foto_parafos", fileNameProperty="img3")
     * @var File
     */
    private $imgFile3;

    /**
     * @ORM\Column(type="string", length=64,nullable=true)
     */
    private $img3;

    /**
     * @ORM\Column(type="string", length=64,nullable=true)
     */
    private $title;

    /**
    @ORM\ManyToOne(targetEntity="Noticia",inversedBy="parafos")
    @ORM\JoinColumn(name="noticia_id", referencedColumnName="id")
     **/
    private $noticia;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @param mixed $noticia
     */
    public function setNoticia($noticia)
    {
        $this->noticia = $noticia;
    }

    /**
     * @return mixed
     */
    public function getNoticia()
    {
        return $this->noticia;
    }



    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImgFile(File $image = null)
    {

        $this->imgFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }
    public function setImgFile2(File $image = null)
    {

        $this->imgFile2 = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }
    public function setImgFile3(File $image = null)
    {

        $this->imgFile3 = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }
    /**
     * @return mixed
     */
    public function getImgFile()
    {
        return $this->imgFile;
    }
    /**
     * @param mixed $img
     */
    public function setImg($img)
    {
        $this->img = $img;
    }
    /**
     * @param mixed $img
     */
    public function setImg2($img)
    {
        $this->img2 = $img;
    }
    /**
     * @param mixed $img
     */
    public function setImg3($img)
    {
        $this->img3 = $img;
    }
    /**
     * @return mixed
     */
    public function getImg()
    {
        return $this->img;
    }
    /**
     * @return File
     */
    public function getImgFile2()
    {
        return $this->imgFile2;
    }


    /**
     * @return mixed
     */
    public function getImg2()
    {
        return $this->img2;
    }

    /**
     * @return File
     */
    public function getImgFile3()
    {
        return $this->imgFile3;
    }


    /**
     * @return mixed
     */
    public function getImg3()
    {
        return $this->img3;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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


}