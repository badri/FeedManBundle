<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\FeedManBundle\Controller;
use Mautic\CoreBundle\Controller\FormController as CommonFormController;
use MauticPlugin\FeedManBundle\Form\FeedType;
use MauticPlugin\FeedManBundle\Entity\Feed;
use MauticPlugin\FeedManBundle\Entity\FeedData;
use Symfony\Component\HttpFoundation\Response;	
$vendorDir = dirname(dirname(__FILE__));
require_once($vendorDir."/library/SimplePie.php");
use SimplePie;

/**
 * Class DefaultController
 */
class DefaultController extends CommonFormController 
{
	public $cacheDir = null;
	public $simplePieObj = null;

	public function __construct(){
		$this->cacheDir = dirname(dirname(dirname(dirname(__FILE__))));
		$this->simplePieObj = new SimplePie();
		$this->cacheDir = $this->cacheDir.'/app/cache/feed_cache';
		if(!file_exists($this->cacheDir)){
			mkdir($this->cacheDir);
		}
		
		$this->simplePieObj->set_cache_location($this->cacheDir);

	}
    /**
     * @param int $objectId
     * @param int $page
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction($page=1)
    {	
		$delIds = $this->request->get('ids');
		$delId = $this->request->get('objectId');
		
		if(empty($delIds) && empty($delId) ){
			
			 //set some permissions
			$permissions = $this->factory->getSecurity()->isGranted('plugin:feedMan:feed:create',"RETURN_ARRAY");
			//$permissions['plugin:feedman:feed:create'] =4;
			$feed = new Feed();	
			
			$feedRepo = $this->getDoctrine()->getRepository('FeedManBundle:Feed');
			$query = $feedRepo->createQueryBuilder('f')->orderBy('f.id', 'DESC')->getQuery();
			$feeds = $query->getResult();
			$viewParameters = array(
				'security'    => $this->factory->getSecurity(),
				'tmpl'        => $this->request->get('tmpl', 'index'),
				'feeds' => $feeds
			);
			return $this->delegateView(array(
				  'viewParameters'  => $viewParameters,
				'contentTemplate' => 'FeedManBundle:Default:list.html.php',
				'passthroughVars' => array(
					'activeLink'     => '#feedman_index',
					'mauticContent'  => 'mydemo',
					'route'          => $this->generateUrl('feedman_index')
				),
			
			 ));
		
		}else{
			
			$ids = array();
			if($delIds){
				$ids = $delIds;
				$ids = json_decode($ids);
			}else if($delId){
				$ids[]= $delId;
			}

            $deleteIds = array();
			$deleteFeedData = array();
           
			// Loop over the IDs to perform access checks pre-delete
            foreach ($ids as $objectId) {
				$entity =$this->getDoctrine()->getRepository('FeedManBundle:Feed')->getEntity($objectId);
				
                //$entity = $model->getEntity($objectId);
				
                if ($entity === null) {
                    $flashes[] = array(
                        'type'    => 'error',
                        'msg'     => 'feedman.feed.error.notfound',
                        'msgVars' => array('%id%' => $objectId)
                    );
                } else {
					$feedsData = $this->getDoctrine()->getRepository('FeedManBundle:FeedData')->findBy( array('feed_id' => $objectId));
                    $deleteIds[] = $entity;
					$deleteFeedData[]= $feedsData;
                }
            }
			
			if (!empty($deleteIds)) {

                $entities = $this->getDoctrine()->getRepository('FeedManBundle:Feed')->deleteEntities($deleteIds);
				if(count($deleteFeedData) > 0){
					foreach($deleteFeedData as $fData){
						$feedDataEntities = $this->getDoctrine()->getRepository('FeedManBundle:FeedData')->deleteEntities($fData);
					}
				}
                $flashes[] = array(
                    'type'    => 'notice',
                    'msg'     => 'feedman.feed.notice.batch_deleted',
                    'msgVars' => array(
                        '%count%' => count($entities)
                    )
                );
            }
			$feedRepo = $this->getDoctrine()->getRepository('FeedManBundle:Feed');
			$query = $feedRepo->createQueryBuilder('f')->orderBy('f.id', 'DESC')->getQuery();
			$feeds = $query->getResult();
			
			$viewParameters = array(
				'security'    => $this->factory->getSecurity(),
				'tmpl'        => $this->request->get('tmpl', 'index'),
				'feeds' => $feeds
			);
		
			return $this->delegateView(array(
				  'viewParameters'  => $viewParameters,
				'contentTemplate' => 'FeedManBundle:Default:list.html.php',
				'passthroughVars' => array(
					'activeLink'     => '#feedman_index',
					'mauticContent'  => 'mydemo',
					'route'          => $this->generateUrl('feedman_index')
				),
			
			 ));
		}
    }


	 public function addAction(){

		 //set some permissions
        $permissions = $this->factory->getSecurity()->isGranted('plugin:feedMan:feed:create',"RETURN_ARRAY");
		$validator = $this->get('validator');
		//$permissions['plugin:feedman:feed:create'] =4;
		// get lead list
		$leadList  = $this->getDoctrine()->getRepository('MauticLeadBundle:LeadList')->findAll();
		$listLead = array();
		foreach($leadList as $leadL){
			$listLead[$leadL->getId()] = $leadL->getName();
		}
		$feed = new Feed();	
		
		$feedRunOn = $this->factory->getParameter('feed_run_on');
		
		//$feedModel = $this->factory->getModel('plugin.feedMan.feed');
		//	$this->createForm(FeedType::class, $feed);
		$form = $this->createFormBuilder($feed)
			->add('name', 'text',array(
			 'label' => 'feedman.feed.addfeedname',
				'attr' => array('class' => 'form-control')))
			->add('url','text',array(
			 'label' => 'feedman.feed.addfeedurl',
				'attr' => array('class' => 'form-control')))
			->add('schedule_day', 'choice', array(
				'label' => 'feedman.feed.addscheduleday',
				'multiple' => true,
				'choices' => $feedRunOn,
				'attr' => array('class' => 'width:300px')))
			->add('leadlist_id', 'choice', array(
				'label' => 'feedman.feed.addselectlist',
				'multiple' => true,
				'choices' => $listLead,
				'attr' => array('style' => 'width:300px', 'customattr' => 'customdata'),
				'data' => array(1, 2),
			))->add('save','submit', array('attr' => array('class' => 'btn button'),'label' => 'feedman.feed.addfeed'))->getForm();
		 $form->handleRequest($this->request);
		 if ($form->isSubmitted()){
			 if ($form->isValid()) {
			
				$leadListIds = $form->getData()->getLeadListId();
				if(count($leadListIds) > 0){
					$feed->setLeadListId(implode(',',$leadListIds));
				}
				$now = date("Y-m-d h:i:s");
				$feed->setDate($now);
				$em = $this->getDoctrine()->getManager();
				$em->persist($feed);
				$em->flush();
				$router = $this->factory->getRouter();
				
				$flashes[] = array(
					'type' => 'notice',
					'msg'  => 'feedman.feed.notice.addsuccess',
				);
			   
				$postActionVars = array(
					'returnUrl'       => $this->generateUrl('feedman_index'),
					
				);
			
				/**** Import feeds data */		
				$this->simplePieObj->set_feed_url($form->getData()->getUrl());
				$this->simplePieObj->init();
				foreach ($this->simplePieObj->get_items() as $item) {
					$feedData = new FeedData();
					$feedData->setFeedId( $feed->getId());
					$feedData->setLink($item->get_link());
					$feedData->setAuthor($item->get_author()->get_name());
					$feedData->setTitle($item->get_title());
					$feedData->setDescription($item->get_content());
					$feedData->setPubDate($item->get_date('Y-m-d H:i:s'));
					$em->persist($feedData);
					$em->flush();
			   }
				return $this->redirect($this->generateUrl('feedman_selectfeed',array('feedid'=>$feed->getId())));
			}
		 } 
		 $viewParameters = array(
			'form' => $form->createView(),
         //   'permissions' => $permissions,
            'security'    => $this->factory->getSecurity(),
            'tmpl'        => $this->request->get('tmpl', 'index')
        );
		return $this->delegateView(array(
			  'viewParameters'  => $viewParameters,
            'contentTemplate' => 'FeedManBundle:Default:index.html.php',
            'passthroughVars' => array(
                'activeLink'     => '#feedman_index',
                'mauticContent'  => 'mydemo',
                'route'          => $this->generateUrl('feedman_addfeed')
            ),
		
        ));
	 
	 }


	 public function editAction(){
		
		$fId = $this->request->get('objectId');
		if(!empty($fId)){
		
			 //set some permissions
			$permissions = $this->factory->getSecurity()->isGranted('plugin:feedMan:feed:create',"RETURN_ARRAY");
			$validator = $this->get('validator');
			// get lead list
			$leadList  = $this->getDoctrine()->getRepository('MauticLeadBundle:LeadList')->findAll();
			$listLead = array();
			foreach($leadList as $leadL){
				$listLead[$leadL->getId()] = $leadL->getName();
			}
			$em = $this->getDoctrine()->getManager();
			$feed = $em->getRepository('FeedManBundle:Feed')->find($fId);
			$feeds = $this->getDoctrine()->getRepository('FeedManBundle:FeedData')->findBy( array('feed_id' => $fId));
			// selected lead lists
			$selectedLeadLists = $feed->getLeadListId();
			$selectedLeadLists = explode(',',$selectedLeadLists);
			$choiceData = array();
			
			if(count($selectedLeadLists)  > 0 && count($listLead) > 0 ){
				foreach($listLead as $k => $leadList){
					if(in_array($k,$selectedLeadLists)){
						$choiceData[] = $k; 
					}
				}
			}
			$feedRunOn = $this->factory->getParameter('feed_run_on');

			$form = $this->createFormBuilder($feed)
				->add('name', 'text',array(
				 'label' => 'feedman.feed.addfeedname',
					'attr' => array('class' => 'form-control')))
				->add('url','text',array(
				 'label' => 'feedman.feed.addfeedurl',
					'attr' => array('class' => 'form-control')))
				->add('schedule_day', 'choice', array(
				'label' => 'feedman.feed.addscheduleday',
				'multiple' => true,
				'choices' => $feedRunOn,
				'attr' => array('class' => 'width:300px')))
			->add('leadlist_id', 'choice', array(
					'label' => 'feedman.feed.addselectlist',
					'multiple' => true,
					'choices' => $listLead,
					'attr' => array(),
					'data' => $choiceData
				))
				->add('save','submit', array('attr' => array('class' => 'btn button'),'label' => "feedman.feed.editfeed"))->getForm();
			 $form->handleRequest($this->request);
			 if ($form->isSubmitted()){
				 if ($form->isValid()) {
			
					$leadListIds = $form->getData()->getLeadListId();
					if(count($leadListIds) > 0){
						$feed->setLeadListId(implode(',',$leadListIds));
					}
					
					$selectFeedType = $this->request->get('select-feed');
					$selectedFeeds = $this->request->get('id');
					$lastXXNo = $this->request->get('last_xx_no');
					
					switch($selectFeedType){
						case 'sendall':
							$feed->setSendFeedType('all');
						break;
						case 'last_xx':
							$feed->setSendFeedType('last_xx');
							$feed->setSendFeed($lastXXNo);
						break;
						case 'selected':
							$feed->setSendFeedType('selected');
							if(count($selectedFeeds) > 0){
								$selectedFeedsString = implode(",",$selectedFeeds);						
							}
							$feed->setSendFeed($selectedFeedsString);
							
						break;
						default:
							$feed->setSendFeedType('all');
						break;
					}
					$em->persist($feed);
					$em->flush();
					
					$flashes[] = array(
						'type' => 'notice',
						'msg'  => 'feedman.feed.notice.updatesuccess',
					);
				   
					
					
					$feedRepo = $this->getDoctrine()->getRepository('FeedManBundle:Feed');
					$query = $feedRepo->createQueryBuilder('f')->orderBy('f.id', 'DESC')->getQuery();
					$feds = $query->getResult();

					$viewParameters = array(
					'security'    => $this->factory->getSecurity(),
					'tmpl'        => $this->request->get('tmpl', 'index'),
					'feeds' => $feds
					);

					return $this->delegateView(array(
					'viewParameters'  => $viewParameters,
					'contentTemplate' => 'FeedManBundle:Default:list.html.php',
					'passthroughVars' => array(
					'activeLink'     => '#feedman_index',
					'mauticContent'  => 'mydemo',
					'route'          => $this->generateUrl('feedman_index')
					),

					));
				}
					
			 } 
			  
			 $viewParameters = array(
				'form' => $form->createView(),
				'security'    => $this->factory->getSecurity(),
				'tmpl'        => $this->request->get('tmpl', 'index'),
				'feeds' =>   $feeds,
				'currentFeed' => $feed,
			);
			$t = $this->generateUrl('feedman_editfeed',$feed->getId());
			$t = $t.'/'.$feed->getId();

			return $this->delegateView(array(
				  'viewParameters'  => $viewParameters,
				'contentTemplate' => 'FeedManBundle:Default:edit.html.php',
				'passthroughVars' => array(
					'activeLink'     => '#feedman_index',
					'mauticContent'  => 'mydemo',
					'route'          => $t
				),
			
			));
		}
	 
	 }


	 public function selectfeedAction($feedId=''){
		$feedId = $this->request->get('feedid');
		$feed_send_type = $this->factory->getParameter('feed_send_type');
		
		 $feedData = new FeedData();	
		 $feed = new Feed();
		 $feeds = $this->getDoctrine()->getRepository('FeedManBundle:FeedData')->findBy( array('feed_id' => $feedId));
		 $em = $this->getDoctrine()->getManager();
		 $currentFeed = $em->getRepository('FeedManBundle:Feed')->find($feedId);
		 if (!$currentFeed) {
			throw $this->createNotFoundException(
				'feedman.feed.error.notfound'.$feedId
			);
		}
		$form = $this->createFormBuilder($feed)->add('send_feed_type','choice',array(
				'choices'  =>$feed_send_type,
				'multiple' => false,
				'expanded' =>true,
			))->getForm();	
		 if ($this->request->isMethod('POST')) {
			 
			$selectFeedType = $this->request->get('select-feed');
			$selectedFeeds = $this->request->get('id');
			$lastXXNo = $this->request->get('last_xx_no');
			
			// add feeds to be emailed
			$emailFeeds = array();
			switch($selectFeedType){
				case 'sendall':
					$currentFeed->setSendFeedType('all');
					$emailFeeds = $feeds;
				break;
				case 'last_xx':
					$currentFeed->setSendFeedType('last_xx');
					$currentFeed->setSendFeed($lastXXNo);
					for($i=0; $i < $lastXXNo; $i++){
						$emailFeeds[] = $feeds{$i};
					}
				break;
				case 'selected':
					$currentFeed->setSendFeedType('selected');
					if(count($selectedFeeds) > 0){
						$selectedFeedsString = implode(",",$selectedFeeds);						
					}
					$currentFeed->setSendFeed($selectedFeedsString);
					foreach($feeds as $f){
						if(in_array($f->getId(),$selectedFeeds)){
							$emailFeeds[] = $f;
						}
					}
				break;
				default:
					$currentFeed->setSendFeedType('all');
					$currentFeed->setSendFeedType('all');
					$emailFeeds = $feeds;
				break;
			}
			$em->flush();
		
			if(count($emailFeeds) > 0){
				// the message
				/*$from='gagandeep@pelbox.com';
				$msg = "Following are the feeds updates.";
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
				$headers .= 'From: ' . $from . "\r\n";
				$headers .= 'Reply-To: ' .$from . "\r\n";
				$headers .= 'X-Mailer: PHP/' . phpversion();
				$msg.= "<ul>";
				foreach($emailFeeds as $emailF){
					$msg.='<li><div><a href="'.$emailF->getLink().'">'.$emailF->getTitle().'</a></div><div>'.$emailF->getDescription().'</div></li>';
				}
				$msg.='</ul>';
				$to = "gwhite@healthstatus.com ,contact@pelbox.com";
				// send email
				mail($to,"Feeds example",$msg,$headers);*/
				
			}

			return $this->redirect($this->generateUrl('feedman_index'));
		 }
		  $viewParameters = array(
			
         //   'permissions' => $permissions,
            'security'    => $this->factory->getSecurity(),
            'tmpl'        => $this->request->get('tmpl', 'selectfeed'),
			'feeds' =>   $feeds,
			'form' => $form->createView(),
        );
		return $this->delegateView(array(
			  'viewParameters'  => $viewParameters,
            'contentTemplate' => 'FeedManBundle:Default:selectfeed.html.php',
            'passthroughVars' => array(
                'activeLink'     => '#feedman_index',
                'mauticContent'  => 'mydemo',
                'route'          => $this->generateUrl('feedman_selectfeed',array('feedid'=>$feedId ))
            ),
		
        ));
		
	 }

	
}
