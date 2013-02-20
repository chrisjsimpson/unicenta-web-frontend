<?php
if(isset($_GET['p']))
{
$p = $_GET['p'];
}else{
	$p = 'home';
}


//Get all possible pages:

$pages = array(
		array()
	);

/*How to store them:
	
	name:
	table:
	parent?:

//Check request to see if it matches one of them

*/
//Load that page.

	//Determine correct page
	switch($p)
	{
		case 'Clothing':
		$title = 'Discount Clothing';
		break;

		case 'add-product':
		$title = "Add a product";
		break;

		default: //If no match, load homepage
		$title = 'Welcome';
		break; 
	}//End page switch

//Load config file
	require_once('includes/config.incl.php');
//Load global functions
	require_once('includes/global-functions.incl.php');

//Load correct page
	//Load frontend functions
	require_once('includes/functions.incl.php');
	//Initialise template
	require TEMPLATE_INIT;
	//Load header
	require_once('includes/header.incl.php');

	//Load page
	require_once("modules/$p/$p.handler.incl.php"); //Each modules has its own directory & page handler

	//Load footer
	require_once('includes/footer.incl.php');
