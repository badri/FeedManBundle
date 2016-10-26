<?php
$icon = 'list';
$btnClass = 'btn btn-default';
 echo '<a class="'.$btnClass.'" href="' . $view['router']->generate($routeBase . '_index', array_merge(array("objectAction" => $action), $query)) . '" data-toggle="' . $editMode . '"' . $editAttr . $menuLink . ">\n";
                echo '  <i class="fa fa-'.$icon.'"></i>See all feeds'.  "\n";
                echo "</a>\n";