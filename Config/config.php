<?php
/**
 * @package     FeedMan
 * @copyright   2015 Pelbox Solutions.
 * @author      Gagandeep Singh
 * @link        http://pelbox.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

return array(
    'name'        => 'Feed Manager Plugin',
    'description' => 'Enables to add feed send feed emails',
    'version'     => '1.0',
    'author'      => 'Gagandeep Singh',
	'website' => 'http://pelbox.com',
	'routes'   => array(
        'main' => array(
             'feedman_index' => array(
                'path'       => '/feedman/{page}',
                'controller' => 'FeedManBundle:Default:index'
            ),
             'feedman_addfeed' => array(
                'path'       => '/feedman/addfeed',
                'controller' => 'FeedManBundle:Default:add'
            ),
             'feedman_editfeed' => array(
                'path'       => '/feedman/editfeed/{objectId}',
                'controller' => 'FeedManBundle:Default:edit'
            ),
             'feedman_selectfeed' => array(
                'path'       => '/feedman/selectfeed/{feedid}',
                'controller' => 'FeedManBundle:Default:selectfeed'
            )
        )
    ),
	'menu'     => array(
        'main' => array(
            'priority' => 20,
            'items'    => array(
                'feedman.menu.index' => array(
                    'route'     => 'feedman_index',
                    'id'        => 'feedman_root',
                    'iconClass' => 'fa-lightbulb-o'
				)
            )
        )
    ),
	'services' => array(
			'forms' => array(
				'feedman.form.type.feed' => array(
					'class'     => 'MauticPlugin\FeedManBundle\Form\Type\FeedType',
					'arguments' => 'mautic.factory',
					'alias'     => 'feedman_feed'
				)
			),
			'events' => array(
				'mautic.emailtoken.subscriber' => array(
                'class' => 'MauticPlugin\FeedManBundle\EventListener\EmailSubscriber'
            )
        ),
	),
	'parameters' => array(
		'feed_run_on' => array(
					'daily' =>'Daily',
					'weekly' =>'Weekly',
					'monthly'=>'Monthly',
					'sunday'=>'Every Sunday',
					'monday'=>'Every Monday',
					'tuesday'=>'Every Tuesday',
					'wednesday'=>'Every Wednesday',
					'thursday'=>'Every Thursday',
					'friday'=>'Every Friday',
					'saturday'=>'Every Saturday',
					'ist_of_the_month'=>'Ist of the Month',
					'15th_of_the_month'=>'15th of the Month'					
		),
		'feed_send_type'=>array(
			'send_all'=>'Send All',
			'manually_select' => 'Manually Select',
			'last_xx' => 'Last Number'
			
		),
		'status'=>array(
			'active'=>'active',
			'disabled' => 'disabled'			
		)
	
	)
);