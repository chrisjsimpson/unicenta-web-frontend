<?php
/* Main page handler for view-basket script */

//Include paypal config constants
require_once('../unicenta-paypal-config.incl.php');

//Switch to load correct page
switch(getModuleAction())
{
	default:
	$page = 'home';
	break;
}
//Include the requested page
require_once($page . '.incl.php');
