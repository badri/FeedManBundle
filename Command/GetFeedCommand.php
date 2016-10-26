<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace MauticPlugin\FeedManBundle\Command;

use Mautic\CoreBundle\Command\ModeratedCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use MauticPlugin\FeedManBundle\Entity\FeedData;

$vendorDir = dirname(dirname(__FILE__));
require_once($vendorDir."/library/SimplePie.php");
use SimplePie;
class GetFeedCommand extends ModeratedCommand
{
    protected function configure()
    {
        $this
            ->setName('mauticplugin:feedman:getfeeds')
            ->setAliases(
                array(
                    'mauticplugin:feedman:getf',
                    'mauticplugin:feedman:getfeeds',
                )
            )
            ->setDescription('Get feeds from remote server')
            ->addOption('--batch-limit', '-l', InputOption::VALUE_OPTIONAL, 'Set batch size of leads to process per round. Defaults to 300.', 300)
            ->addOption(
                '--max-leads',
                '-m',
                InputOption::VALUE_OPTIONAL,
                'Get feeds from websites',
                false
            )
            ->addOption('--force', '-f', InputOption::VALUE_NONE, 'Force execution even if another process is assumed running.');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container  = $this->getContainer();
        $factory    = $container->get('mautic.factory');
        $translator = $factory->getTranslator();
        $em         = $factory->getEntityManager();
		$output->writeln('<info>Feedman get feeds email status='.date('D').'</info>');
		$listModel = $factory->getModel('lead.list');
		$feeds = $em->getRepository('FeedManBundle:Feed')->getEntities();
		foreach($feeds as $f){
			if($f->getStatus() == 'active'){
				$prevFeeds = $em->getRepository('FeedManBundle:FeedData')->findBy( array('feed_id' => $f->getId()));
				$prevFeedsHash = array();
				if(count($prevFeeds)  > 0){
					foreach($prevFeeds as $prevFeed){
						 $prevFeedsHash[] = $prevFeed->getHash();
					}
				
				}
				$simplePieObj = new SimplePie();
				/**** Import feeds data */		
				$simplePieObj->set_feed_url($f->getUrl());
				$simplePieObj->init();
				foreach ($simplePieObj->get_items() as $item) {
					if(!in_array($item->get_id(true), $prevFeedsHash)){
						$feedData = new FeedData();
						$feedData->setFeedId( $f->getId());
						$feedData->setLink($item->get_link());
						$feedData->setAuthor($item->get_author()->get_name());
						$feedData->setTitle($item->get_title());
						$feedData->setDescription($item->get_content());
						$feedData->setPubDate($item->get_date('Y-m-d H:i:s'));
						$em->persist($feedData);
						$em->flush();
						$output->writeln('<info>Test test='.date('D').'</info>');
					}
			   }
			}
		}
		unset($feeds);
          
        

        $this->completeRun();

        return 0;
    }
}