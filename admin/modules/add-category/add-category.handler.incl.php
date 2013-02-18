<?php
/* Main page handler for add-category script */

//Find pos of first & 
if(strpos($_SERVER['QUERY_STRING'], '&') == 0)
{
	$action = 'add-category-home.incl.php';	
}else{
	$position = strpos($_SERVER['QUERY_STRING'], '&') + 1;
	$action= substr($_SERVER['QUERY_STRING'], $position);
	}

//Switch to load correct page
switch($action)
{
	case 'save':
	$page = 'save';
	break;

	default:
	$page = 'home';
	break;
}

//Include the requested page
require_once($page . '.incl.php');
