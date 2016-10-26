<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

if (isset($buttonFormat)) {
    $buttonGroupTypes = array($buttonFormat);
} else {
    $count = 0;
    // Get a count of buttons
    if (isset($preCustomButtons)) {
        $count += count($preCustomButtons);
    }

    //Build post template custom buttons
    if (isset($customButtons)) {
        $count += count($customButtons);
    } elseif (isset($postCustomButtons)) {
        $count += count($postCustomButtons);
    }

    if (isset($templateButtons)) {
        foreach ($templateButtons as $templateButton) {
            if ($templateButton)
                $count++;
        }
    }

    $buttonGroupTypes = ($count > 4) ? array('button-dropdown') : array('group', 'button-dropdown');
}

$forceVisible = (count($buttonGroupTypes) === 1);
$icon = 'plus';
$btnClass = 'btn btn-default';

 echo '<a class="'.$btnClass.'" href="' . $view['router']->generate($routeBase . '_addfeed', array_merge(array("objectAction" => $action), $query)) . '" data-toggle="' . $editMode . '"' . $editAttr . $menuLink . ">\n";
                echo '  <i class="fa fa-'.$icon.'"></i>Add new feed'.  "\n";
                echo "</a>\n";

echo $view->render('FeedManBundle:Default:confirm.html.php', array(
'message'       => $view["translator"]->trans("feedman.feed.confirmdeletebatch"),
'confirmAction' => $view['router']->generate($routeBase . '_index'),
'template'      => 'batchdelete',
'btnClass'      => $btnClass
));
foreach ($buttonGroupTypes as $groupType) {
    $buttonCount = 0;
    if ($groupType == 'group') {
        echo '<div class="std-toolbar btn-group' . ((!$forceVisible) ? ' hidden-xs hidden-sm' : '') .'">';
        $dropdownOpenHtml = '';
    } else {
        echo '<div class="dropdown-toolbar btn-group' . ((!$forceVisible) ? ' hidden-md hidden-lg' : '') . '">';
        $dropdownOpenHtml  = '<button type="button" class="btn btn-default btn-nospin  dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-caret-down"></i></button>' . "\n";
        $dropdownOpenHtml .= '<ul class="dropdown-menu dropdown-menu-right" role="menu">' . "\n";
    }

    include 'action_button_helper.php';

    echo $view['buttons']->renderPreCustomButtons($buttonCount, $dropdownOpenHtml);

    foreach ($templateButtons as $action => $enabled) {
        if (empty($enabled)) {
            continue;
        }

        if ($buttonCount === 1) {
            echo $dropdownOpenHtml;
        }

        if ($groupType == 'button-dropdown' && $buttonCount > 0) {
            $wrapOpeningTag = "<li>\n";
            $wrapClosingTag = "</li>\n";
        }

        echo $wrapOpeningTag;

        $btnClass = ($groupType == 'group' || $buttonCount === 0) ? 'btn btn-default' : '';

        switch ($action) {
            case 'new':
            case'edit':
                if ($action == 'new') {
                    $icon = 'plus';
                } else {
                    $icon = 'pencil-square-o';
                    $query['objectId'] = $item->getId();
                }

                echo '<a class="'.$btnClass.'" href="' . $view['router']->generate($routeBase . '_addfeed', array_merge(array("objectAction" => $action), $query)) . '" data-toggle="' . $editMode . '"' . $editAttr . $menuLink . ">\n";
                echo '  <i class="fa fa-'.$icon.'"></i> ' . $view['translator']->trans('mautic.core.form.' . $action) . "\n";
                echo "</a>\n";
                break;
        }
        $buttonCount++;

        echo $wrapClosingTag;
    }

    echo $view['buttons']->renderPostCustomButtons($buttonCount, $dropdownOpenHtml);

    echo ($groupType == 'group') ? '</div>' : '</ul></div>';
}

echo $extraHtml;