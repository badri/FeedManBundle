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

class DeleteFeedCommand extends ModeratedCommand
{
    protected function configure()
    {
        $this
            ->setName('mauticplugin:feedman:deletefeeds')
            ->setAliases(
                array(
                    'mauticplugin:feedman:deletef',
                    'mauticplugin:feedman:deletefeeds',
                )
            )
            ->setDescription('Get feeds from remote server')
            ->addOption('--batch-limit', '-l', InputOption::VALUE_OPTIONAL, 'Set batch size of leads to process per round. Defaults to 300.', 300)
            ->addOption(
                '--max-leads',
                '-m',
                InputOption::VALUE_OPTIONAL,
                'Delete sent feeds from database.',
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
		$output->writeln('<info>Feedman delete feeds email status='.date('D').'</info>');
		$repo = $em->getRepository('FeedManBundle:FeedData');
		$fifteenDate = date('Y-m-d', strtotime('-15 days'));
		$q = $repo->createQueryBuilder('f')->where('f.pub_date < :pubDate')->andWhere('f.status = :status')->setParameter('pubDate', $fifteenDate)->setParameter('status', 1)->getQuery();
		$results = $q->getResult();
		$deleteIds = array();
		foreach($results as $result){
			$deleteIds[]= $result;
		}
		if(count($deleteIds) > 0){
			$em->getRepository('FeedManBundle:FeedData')->deleteEntities($deleteIds);
		}
        $this->completeRun();

        return 0;
    }
}