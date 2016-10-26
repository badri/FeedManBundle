<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
$view->extend('MauticCoreBundle:Default:content.html.php');
?>
<?php 


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
?>
<link rel='stylesheet' type='text/css' href="<?php echo $view['assets']->getUrl('plugins/FeedManBundle/assets/css/feedman.css'); ?>" />
<script src="<?php echo $view['assets']->getUrl('plugins/FeedManBundle/assets/js/feedman.js'); ?>"></script>
<div class="panel panel-default bdr-t-wdh-0 mb-0">
   
	<?php echo $view['form']->start($form); ?>
		<div class='row'>
		
		<div class='col-md-12  col-xs-12 col-sm-12'>
			<?php
			
			echo $view['form']->row($form['send_feed_type']) ;
			
			?>		
		
			<div class='send_all feedman-hide'>
				<label><?php echo $view['translator']->trans('feedman.feed.allfeedssend'); ?></label>
			</div>
			<div class='last_xx feedman-hide'>
				<label for ='last_xx_no'><?php echo $view['translator']->trans('feedman.feed.selectnumfeeds'); ?></label> 
				<input type="number" value='1' name="last_xx_no" min="1" max="<?php echo count($feeds); ?>">
			</div>
			<div class="table-responsive chat-channel-list manually_select feedman-hide">
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
								<td><input type='checkbox' name='id[]' value='<?php echo $feed->getId(); ?>' /></td>
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
		<!-- -->
	<?php echo $view['form']->widget($form['save']) ;?>
	<?php echo $view['form']->end($form); ?>

	</div>

    <div class="page-list">
        <?php $view['slots']->output('_content'); ?>
    </div>
</div>

