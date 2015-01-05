<?php
/**
 * Created by PhpStorm.
 * User: Marci
 * Date: 2014.08.29.
 * Time: 13:51
 */

namespace Marton\PortfolioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Marton\PortfolioBundle\Repository\ProjectRepository")
 * @ORM\Table(name="tbl_project")
 */
class Project {


    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $subheader;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $picture;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * @ORM\Column(type="text")
     */
    protected $text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $bitBucketLink = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $screenshotsLink = null;

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setBitBucketLink($bitBucketLink)
    {
        $this->bitBucketLink = $bitBucketLink;
    }

    public function getBitBucketLink()
    {
        return $this->bitBucketLink;
    }

    public function setScreenshotsLink($screenshotsLink)
    {
        $this->screenshotsLink = $screenshotsLink;
    }

    public function getScreenshotsLink()
    {
        return $this->screenshotsLink;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    public function getPicture()
    {
        return $this->picture;
    }

    public function setSubheader($subheader)
    {
        $this->subheader = $subheader;
    }

    public function getSubheader()
    {
        return $this->subheader;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getText()
    {
        return $this->text;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }
} 