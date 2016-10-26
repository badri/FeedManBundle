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

$view['slots']->set('actions', $view->render('FeedManBundle:Default:edit_actions.html.php', array(
    'templateButtons' => array(
        'new'    => $permissions['email:emails:create']
    ),
    'routeBase' => 'feedman'
)));

?>

 
 <div class="col-md-12">
<div class="panel panel-default bdr-t-wdh-0 mb-0">
    <?php /*echo $view->render('MauticCoreBundle:Helper:list_toolbar.html.php', array(
        'searchValue' => $searchValue,
        'searchHelp'  => 'mautic.form.form.help.searchcommands',
        'searchId'    => 'form-search',
        'action'      => $currentRoute,
        'routeBase'   => 'form',
        'templateButtons' => array(
            'delete' => $permissions['form:forms:deleteown'] || $permissions['form:forms:deleteother']
        ),
        'preCustomButtons' => array(
            array(
                'confirm'      => array(
                    'message'         => $view['translator']->trans('mautic.form.confirm_batch_rebuild'),
                    'confirmText'     => $view['translator']->trans("mautic.form.rebuild"),
                    'confirmAction'   => $view['router']->generate('mautic_form_action', array_merge(array('objectAction' => 'batchRebuildHtml'))),
                    'tooltip'         => $view['translator']->trans('mautic.form.rebuild.batch_tooltip'),
                    'iconClass'       => 'fa fa-fw fa-refresh',
                    'btnText'         => false,
                    'btnClass'        => 'btn btn-sm btn-default',
                    'precheck'        => 'batchActionPrecheck',
                    'confirmCallback' => 'executeBatchAction'
                )
            )
        ),
    )); 
	*/
	echo $view['form']->start($form);
	echo $view['form']->widget($form);
	echo $view['form']->end($form); 
?>

    <div class="page-list">
        <?php $view['slots']->output('_content'); ?>
    </div>
</div>
</div>

