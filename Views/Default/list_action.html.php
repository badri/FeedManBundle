<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

$groupType = 'dropdown';
include 'action_button_helper.php';

if (is_array($item)) {
    $id   = $item['id'];
    $name = $item['name'];
} else {
    $id   = $item->getId();
    $name = $item->getName();
}

if (!isset($route)) {
    $route = 'feedman_index';
}
$routeedit = 'feedman_editfeed';
?>
<div class="input-group input-group-sm">
    <span class="input-group-addon">
        <input type="checkbox" data-target="tbody" data-toggle="selectrow" class="list-checkbox" name="cb<?php echo $id; ?>" value="<?php echo $id; ?>" />
    </span>

    <div class="btn-group">
        <button type="button" class="btn btn-default btn-sm dropdown-toggle btn-nospin" data-toggle="dropdown">
            <i class="fa fa-angle-down "></i>
        </button>
        <ul class="pull-<?php echo $pull; ?> page-list-actions dropdown-menu" role="menu">
            <?php echo $view['buttons']->renderPreCustomButtons($buttonCount); ?>

            <?php if (!empty($templateButtons['edit'])): ?>
            <li>
                <a href="<?php echo $view['router']->generate($routeedit, array_merge(array("objectId" => $id), $query)); ?>" data-toggle="<?php echo $editMode; ?>"<?php echo $editAttr.$menuLink; ?>>
                    <span><i class="fa fa-pencil-square-o"></i> <?php echo $view['translator']->trans('mautic.core.form.edit'); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (!empty($templateButtons['clone'])): ?>
            <li>
                <a href="<?php echo $view['router']->generate($route, array_merge(array("objectAction" => "clone", "objectId" => $id), $query)); ?>" data-toggle="ajax"<?php echo $menuLink; ?>>
                    <span><i class="fa fa-copy"></i> <?php echo $view['translator']->trans('mautic.core.form.clone'); ?></span>
                </a>
            </li>
            <?php endif; ?>

            <?php if (!empty($templateButtons['delete'])): ?>
            <li>
                <?php echo $view->render('MauticCoreBundle:Helper:confirm.html.php', array(
                    'btnClass'      => false,
                    'message'       => $view["translator"]->trans('feedman.feed.confirmdelete', array("%name%" => $name . " (" . $id . ")")),
                    'confirmAction' => $view['router']->generate($route, array_merge(array("objectAction" => "delete", "objectId" => $id), $query)),
                    'template'      => 'delete'
                )); ?>
            </li>
            <?php endif; ?>

            <?php echo $view['buttons']->renderPostCustomButtons($buttonCount); ?>
        </ul>
    </div>
</div>
