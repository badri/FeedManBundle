<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
$currentView ='edit';

$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('mauticContent', 'form');
$view['slots']->set("headerTitle", $view['translator']->trans('feedman.feed.feeds'));
/*
$view['slots']->set('actions', $view->render('MauticCoreBundle:Helper:page_actions.html.php', array(
    'templateButtons' => array(
        'new'    => $permissions['plugin:feedMan:feed:create']
    ),
    'routeBase' => 'feedman',
    'langVar'   => 'feedman.feed'
)));
*/
$view['slots']->set('actions', $view->render('FeedManBundle:Default:edit_actions.html.php', array(
    'templateButtons' => array(
        'new'    => $permissions['email:emails:create']
    ),
    'routeBase' => 'feedman'
)));
?>
 <div class="col-md-12">
	<div class="panel panel-default bdr-t-wdh-0 mb-0">
    <?php 
		echo $view['form']->start($form);
		echo $view['form']->row($form['name']) ;
		echo $view['form']->row($form['url']) ;
		echo $view['form']->row($form['schedule_day']) ;
		echo $view['form']->row($form['leadlist_id']) ;
		echo $view['form']->row($form['status']) ;
		echo $view['form']->row($form['send_feed_type']) ;
	?>
		<div class='last_xx feedman-hide'>
				<label for ='last_xx_no'><?php echo $view['translator']->trans('feedman.feed.selectnumfeeds'); ?></label> 
				<input type="number" value='1' name="last_xx_no" min="1" max="<?php echo count($feeds); ?>">
		</div>
		<div class="table-responsive manually_select selected feedman-hide">
				<table class="table table-hover table-striped table-bordered">
					<thead>
						<tr>
							<th><?php echo $view['translator']->trans('feedman.feed.selectfeed'); ?></th>
							<th><?php echo $view['translator']->trans('feedman.feed.title'); ?></th>
							<th><?php echo $view['translator']->trans('feedman.feed.link'); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
							if($feeds && count($feeds) > 0){
								foreach($feeds as $feed){
						?>
							<tr>
								<td><input type='checkbox' id='feed-<?php echo $feed->getId(); ?>' name='id[]' value='<?php echo $feed->getId(); ?>' /></td>
								<td><?php echo $feed->getTitle(); ?></td>
								<td><a _target='blank' href='<?php echo $feed->getLink(); ?>'> <?php echo $feed->getLink(); ?></a></td>					
							</tr>
			
						<?php
								}
							}
						?>
					</tbody>
				</table>
		</div>
	
<?php
	echo $view['form']->widget($form['save']) ;
	echo $view['form']->end($form); 

?>


    <div class="page-list">
        <?php $view['slots']->output('_content'); ?>
    </div>

<link rel='stylesheet' type='text/css' href="<?php echo $view['assets']->getUrl('plugins/FeedManBundle/assets/css/feedman.css'); ?>" />
<script>
	var sendFeedType ='<?php echo $currentFeed->getSendFeedType(); ?>'; 
	var selectedFeedNumber = null;
	var selectedFeeds = null;
	switch(sendFeedType){
		case 'last_xx':
			selectedFeedNumber = '<?php echo $currentFeed->getSendFeed();  ?>';
		break;
		case 'manually_select':
			selectedFeeds = '<?php echo $currentFeed->getSendFeed();  ?>'
		break;
	}
</script>
<script src="<?php echo $view['assets']->getUrl('plugins/FeedManBundle/assets/js/feedman.js'); ?>"></script>
</div>
</div>