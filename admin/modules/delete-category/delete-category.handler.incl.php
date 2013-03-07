<?php
/* Main page handler for add-category script */

//Switch to load correct page
switch(getModuleAction())
{
	case 'delete':
	$page = 'delete';
	break;

	default:
	$page = 'home';
	break;
}


//Include the requested page
require_once($page . '.incl.php');
