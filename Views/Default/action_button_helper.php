<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

//Set vars commonly used
if (!isset($buttonCount)) {
    $buttonCount = 0;
}

//Function used to get identifier string for entity
$nameGetter = (!empty($nameGetter)) ? $nameGetter : 'getName';

//Dropdown direction
if (empty($pull)) {
    $pull = 'left';
}

//Custom query parameters for URLs
if (!isset($query)) {
    $query = array();
}

if (isset($tmpl)) {
    $query['tmpl'] = $tmpl;
}

//Edit mode for edit/actions (allows use of ajaxmodal)
if (!isset($editMode)) {
    $editMode = "ajax";
}

if (!isset($editAttr)) {
    $editAttr = '';
} elseif (is_array($editAttr)) {
    $string = "";
    foreach ($editAttr as $attr => $val) {
        $string .= " $attr=\"$val\"";
    }
    $editAttr = $string;
} else {
    $editAttr = " $editAttr";
}

//Template/common buttons
if (!isset($templateButtons)) {
    $templateButtons = array();
}

//Set langVar to routeBase if not set
if (!isset($langVar) && isset($routeBase)) {
    $langVar = $routeBase;
}

//Set a default button type (group or dropdown)
if (!isset($groupType)) {
    $groupType = 'group';
}
$view['buttons']->setGroupType($groupType);

//Extra HTML to be inserted after the buttons
if (!isset($extraHtml)) {
    $extraHtml = '';
}

//Wrapper such as li
if (!isset($wrapOpeningTag)) {
    $wrapOpeningTag = $wrapClosingTag = '';
}
$view['buttons']->setWrappingTags($wrapOpeningTag, $wrapClosingTag);

//Builder for custom buttons
$menuLink  = (isset($menuLink)) ? " data-menu-link=\"{$menuLink}\"" : '';
$view['buttons']->setMenuLink($menuLink);

//Build pre template custom buttons
if (!isset($preCustomButtons)) {
    $preCustomButtons = array();
}

//Build post template custom buttons
if (isset($customButtons)) {
    $postCustomButtons = $customButtons;
} elseif (!isset($postCustomButtons)) {
    $postCustomButtons = array();
}

$view['buttons']->setCustomButtons($preCustomButtons, $postCustomButtons);