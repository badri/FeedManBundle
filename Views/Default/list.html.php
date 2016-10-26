<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
$view->extend('MauticCoreBundle:Default:content.html.php');

$view['slots']->set('mauticContent', 'form');
$view['slots']->set("headerTitle", $view['translator']->trans('feedman.feed.feeds'));
?>
<script src="<?php echo $view['assets']->getUrl('plugins/FeedManBundle/assets/js/feedman.js'); ?>"></script>

<?php
//$view['slots']->set('actions',);
$view['slots']->set('actions', $view->render('FeedManBundle:Default:page_actions.html.php', array(
    'templateButtons' => array(
        'new'    => $permissions['email:emails:create']
    ),
    'routeBase' => 'feedman'
)));
?>

<div class="panel panel-default bdr-t-wdh-0 mb-0">
    <div class="page-list">
        <?php
			if(count($feeds) > 0 ){
		?>
		<div class="table-responsive chat-channel-list">
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
					<?php
						echo $view->render('FeedManBundle:Default:tableheader.html.php', array(
						'checkall' => 'true'
						));
					?>
						
						<th><?php echo $view['translator']->trans('feedman.feed.name'); ?></th>
						<th><?php echo $view['translator']->trans('feedman.feed.url'); ?></th>
						<th><?php echo $view['translator']->trans('feedman.feed.schedule_day'); ?></th>
						<th><?php echo $view['translator']->trans('feedman.feed.send_feeds'); ?></th>
						<th><?php echo $view['translator']->trans('feedman.feed.status'); ?></th>
						<th><?php echo $view['translator']->trans('feedman.feed.date_added'); ?></th>
						<th><?php echo $view['translator']->trans('feedman.feed.shortcode'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
			
						foreach($feeds as $feed){
					?>
						<tr>
							<td>
							<?php
							  echo $view->render('FeedManBundle:Default:list_action.html.php', array(
								'item'            => $feed,
								'templateButtons' => array(
									'edit'       => 1,
									'delete'     => 1,
								
								),
								'routeBase'       => 'feedman_editfeed',
								'customButtons'   => $customButtons
							));
							?>
							</td>
							<td><?php echo $feed->getName(); ?></td>
							<td><a _target='blank' href='<?php echo $feed->getUrl(); ?>'> <?php echo $feed->getUrl(); ?></a></td>
							<td><?php 
									
									if($feed->getScheduleDay() != null){
										if(count( $feed->getScheduleDay())){
											$scheduleDay = $feed->getScheduleDay();

											echo strtoupper(str_replace("_"," ",implode(',',$scheduleDay)));
											
										} 
									}
					
								?>
							</td>
							<td><?php 
									$sendFeedType = str_replace('_',' ',$feed->getSendFeedType()); 
									$sendFeedType = str_replace('xx',$feed->getSendFeed(),$sendFeedType);
									echo strtoupper($sendFeedType);
								?>
							</td>
							<td><?php echo strtoupper($feed->getStatus()); ?></td>
							<td><?php echo $feed->getDate()->format('Y-m-d H:i:s'); ?></td>
							<td>{feedman id=<?php echo $feed->getId(); ?>}</td>
							
						</tr>
		
					<?php
						}
					?>
				</tbody>
			</table>
		</div>
		 <?php
			}
		?>
</div>

