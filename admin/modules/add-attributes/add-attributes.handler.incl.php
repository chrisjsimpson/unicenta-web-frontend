<?php
/* Main page handler for add-attributes handler script */

//Switch to load correct page
switch(getModuleAction())
{
	case 'chooseAttributes':
		$page = 'chooseAttributes';
	break;

	case 'save':
		$page = 'saveProductAttributes';
	break;

	default:
		$page = 'home';
	break;
}
//Include the requested page
require_once($page . '.incl.php');
