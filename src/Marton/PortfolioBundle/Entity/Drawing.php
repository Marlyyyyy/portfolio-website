<?php
/**
 * Created by PhpStorm.
 * User: Marci
 * Date: 2014.09.02.
 * Time: 14:58
 */

namespace Marton\PortfolioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Marton\PortfolioBundle\Repository\DrawingRepository")
 * @ORM\Table(name="tbl_drawing")
 */


class Drawing {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     */
    protected $year;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $thumbnail;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $image;

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setYear($year)
    {
        $this->year = $year;
    }

    public function getYear()
    {
        return $this->year;
    }

    public function getId()
    {
        return $this->id;
    }
} 