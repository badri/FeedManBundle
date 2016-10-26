<?php
// plugins/HelloWorldBundle/Entity/World.php

namespace MauticPlugin\FeedManBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Mautic\CategoryBundle\Entity\Category;
use Mautic\CoreBundle\Entity\CommonEntity;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Url;
use Symfony\Component\Validator\Mapping\ClassMetadata;
/**
 * Class Feed
 * @ORM\Table(name="feed")
 * @ORM\Entity(repositoryClass="MauticPlugin\FeedManBundle\Entity\FeedRepository")
 */
class Feed extends CommonEntity
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;
	
	/**
     * @ORM\Column(type="string",length=1024)
     */
	private $url;

	/**
     * @ORM\Column(type="string",length=255)
     */
	private $leadlist_id;
	

	/**
     * @ORM\Column(type="simple_array")
     */
	private $schedule_day;

	/**
     * @ORM\Column(type="string", nullable=true)
     */
	private $send_feed;
	/**
     * @ORM\Column(type="string", nullable=true)
     */
	private $send_feed_type;
	
	/**
     * @ORM\Column(type="string",length=50)
     */
	private $status;

	
	/**
     * @ORM\Column(type="datetime")
     */
	private $date;

    public static function loadValidatorMetadata(ClassMetadata $metadata) {
        $metadata->addPropertyConstraint('name', new NotBlank());
        $metadata->addPropertyConstraint('url',new NotBlank());
        $metadata->addPropertyConstraint('url', new Url());
        $metadata->addPropertyConstraint('leadlist_id',new NotBlank());
        $metadata->addPropertyConstraint('schedule_day',new NotBlank());
       // $metadata->addPropertyConstraint('send_feed_type',new NotBlank());
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }
 
    /**
     * @return mixed
     */
    public function getScheduleDay()
    {
		
        return $this->schedule_day;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
		
        return $this->date;
    }

    /**
     * @return mixed
     */
    public function getLeadListId()
    {
		
        return $this->leadlist_id;
    }

    /**
     * @return mixed
     */
    public function getSendFeed()
    {
		
        return $this->send_feed;
    }
    /**
     * @return mixed
     */
    public function getStatus()
    {
		
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getSendFeedType()
    {
		
        return $this->send_feed_type;
    }

    /**
     * @param mixed $name
     *
     * @return World
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param mixed $url
     *
     * @return World
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param mixed $date
     *
     * @return World
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @param mixed $leadlist_id
     *
     * @return World
     */
    public function setLeadListId($leadlist_id)
    {
        $this->leadlist_id = $leadlist_id;

        return $this;
    }

    /**
     * @param mixed $schedule_day
     *
     * @return World
     */
    public function setScheduleDay($schedule_day)
    {
        $this->schedule_day = $schedule_day;

        return $this;
    }

 /**
     * @return mixed
     */
    public function setSendFeed($send_feed)
    {
		$this->send_feed = $send_feed;

        return $this;
  
    }
	 /**
     * @return mixed
     */
    public function setSendFeedType($send_feed_type)
    {
		$this->send_feed_type = $send_feed_type;

        return $this;
  
    }
	 /**
     * @return mixed
     */
    public function setStatus($status)
    {
		$this->status = $status;

        return $this;
  
    }


}