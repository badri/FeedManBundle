<?php
/**
 * @package     Mautic
 * @copyright   2015 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\FeedManBundle\Helper;

use Mautic\CoreBundle\Factory\MauticFactory;
use MauticPlugin\FeedManBundle\Entity\Feed;
use MauticPlugin\FeedManBundle\Entity\FeedData;

/**
 * Class FeedHelper
 */
class FeedHelper
{

    /**
     * @var MauticFactory
     */
    protected $factory;

    

    /**
     * @param MauticFactory $factory
     * @param               $mailer
     * @param null          $from
     */
    public function __construct(MauticFactory $factory)
    {
		
        $this->factory = $factory;
     
    }

	public function getFeedsById($id){
		//print_r($id);
		//die;

		$feed=$this->factory->getEntityManager()->getRepository('FeedManBundle:Feed')->getEntity($feedIdArray[1]);
		return $feeds;
	}

   
}
