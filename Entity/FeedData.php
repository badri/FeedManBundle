<?php
// plugins/HelloWorldBundle/Entity/World.php

namespace MauticPlugin\FeedManBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CategoryBundle\Entity\Category;
use Mautic\CoreBundle\Entity\CommonEntity;

/**
 * Class FeedData
 * @ORM\Table(name="feeddata")
 * @ORM\Entity(repositoryClass="MauticPlugin\FeedManBundle\Entity\FeedRepository")
 */
class FeedData extends CommonEntity
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @ORM\Column(type="integer")
    */
    private $feed_id;

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
    */
    private $hash;

    /**
     * @ORM\Column(type="string")
     */
    private $title;
	
	/**
     * @ORM\Column(type="text")
     */
	private $description;


	/**
     * @ORM\Column(type="string",length=1024)
     */
	private $link;

	/**
     * @ORM\Column(type="string",length=50)
     */
	private $author;
	
	/**
     * @ORM\Column(type="integer",options={"default"="0"})
     */
	private $status=0;

	/**
     * @ORM\Column(type="datetime")
     */
	private $pub_date;
  

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
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return mixed
     */
    public function getFeedId()
    {
        return $this->feed_id;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }
 
    /**
     * @return mixed
     */
    public function getTitle()
    {
		
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
		
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
		
        return $this->author;
    }
    /**
     * @return mixed
     */
    public function getStatus()
    {
		
        return $this->status;
    }

  
	/**
     * @param mixed $pub_date
     *
     * @return World
     */
    public function getPubDate($pub_date)
    {
        return $this->pub_date;
    }

    /**
     * @param mixed $feed_id
     *
     * @return World
     */
    public function setFeedId($feed_id)
    {
        $this->feed_id = $feed_id;

        return $this;
    }

    /**
     * @param mixed $title
     *
     * @return World
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }
	
	/**
     * @return mixed
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @param mixed $description
     *
     * @return World
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param mixed $status
     *
     * @return World
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }
    /**
     * @param mixed $description
     *
     * @return World
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @param mixed $link
     *
     * @return World
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @param mixed $pub_date
     *
     * @return World
     */
    public function setPubDate($pub_date)
    {
        $this->pub_date = $pub_date;

        return $this;
    }

 

  

 
}