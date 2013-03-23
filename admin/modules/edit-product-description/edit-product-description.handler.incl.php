<?php
/* Main page handler for edit product description  script */

//Switch to load correct page
switch(getModuleAction())
{
		
	case 'edit':
	$page = 'edit';
	break;
	
	case 'save':
	$page = 'save';
	break;

	default:
	$page = 'home';  //Ask user to select product to edit product desction
	break;
}


//Include the requested page
require_once($page . '.incl.php');
