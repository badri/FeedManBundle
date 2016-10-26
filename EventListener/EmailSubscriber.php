<?php
// plugins/FeedManBundle/EventListener/EmailSubscriber.php

namespace MauticPlugin\FeedManBundle\EventListener;

use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\EmailBundle\EmailEvents;
use Mautic\EmailBundle\Event\EmailBuilderEvent;
use Mautic\EmailBundle\Event\EmailSendEvent;
use MauticPlugin\FeedManBundle\Entity\Feed;
use MauticPlugin\FeedManBundle\Entity\FeedData;
use Doctrine\ORM\EntityManager;
use Mautic\CoreBundle\Factory\MauticFactory;
/**
 * Class EmailSubscriber
 */
class EmailSubscriber extends CommonSubscriber
{

    /**
     * @return array
     */
    static public function getSubscribedEvents()
    {
        return array(
            EmailEvents::EMAIL_ON_BUILD   => array('onEmailBuild', 0),
            EmailEvents::EMAIL_ON_SEND    => array('onEmailGenerate', 0),
            EmailEvents::EMAIL_ON_DISPLAY => array('onEmailGenerate', 0)
        );
    }

    /**
     * Register the tokens and a custom A/B test winner
     *
     * @param EmailBuilderEvent $event
     */
    public function onEmailBuild(EmailBuilderEvent $event)
    {
        // Add email tokens
      //  $content = $this->templating->render('FeedManBundle:Default:token.html.php');
       // $event->addTokenSection('helloworld.token', 'plugin.FeedManBundle.header', $content);

        
    }

    /**
     * Search and replace tokens with content
     *
     * @param EmailSendEvent $event
     */
    public function onEmailGenerate(EmailSendEvent $event)
    {
        // Get content
        $content = $event->getContent();
		//substr($username, 0, strpos($username, '@'));
		//$shortCode = substr($content, 49, strpos($content, '}'));

		$startShortCode = strpos($content, '{feedman');
		$endShortCode= strpos($content, '}');
		$totalLengthShortCode = $endShortCode - $startShortCode;
		$orgFeedmanCode = substr($content, $startShortCode,$totalLengthShortCode+1);
		$feedmanCode = rtrim($orgFeedmanCode,'}');

		$feedIdArray = explode('=',$feedmanCode); 
			$emailFeeds = array();
        if(count($feedIdArray) > 0 && isset($feedIdArray[1])){
                        $feed_id = $feedIdArray[1];
			$feed = $this->em->find($feed_id,'FeedManBundle:Feed');
			$feeds = $this->em->getRepository('FeedManBundle:FeedData')->findBy( array('feed_id' => $feed->getId()));

			$feedType = $feed->getSendFeedType();
			$sendFeed = $feed->getSendFeed();
			switch($feedType){
				case 'sendall':
					$emailFeeds = $feeds;
				break;
				case 'last_xx':
					for($i=0; $i < $sendFeed; $i++){
						$emailFeeds[] = $feeds{$i};
					}
				break;
				case 'selected':
					$selectedFeeds = explode(",",$sendFeed);						
					foreach($feeds as $f){
						if(in_array($f->getId(),$selectedFeeds)){
							$emailFeeds[] = $f;
						}
					}
				break;
				
			}
		
		}

		$msg = $this->getFeedHtml($emailFeeds);
		$content = str_replace($orgFeedmanCode,$msg,$content);
        // Set updated content
        $event->setContent($content);
    }
	
	private function getFeedHtml($emailFeeds){
		$msg='';
		if(count($emailFeeds) > 0){			
			$msg.= "<ul>";
			foreach($emailFeeds as $emailF){
				$msg.='<li><div><a href="'.$emailF->getLink().'">'.$emailF->getTitle().'</a></div><div>'.$emailF->getDescription().'</div></li>';
			}
			$msg.='</ul>';

		}
		return $msg;
	
	}

}
