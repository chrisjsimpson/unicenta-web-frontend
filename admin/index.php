<?php
if(isset($_GET['p']))
{
$p = $_GET['p'];
}else{
	$p = 'home';
}

	//Determine correct page
	switch($p)
	{
		case 'add-category':
		$title = 'New category';
		break;

		case 'add-product':
		$title = "Add a product";
		break;
		
		case 'add-attributes':
		$title = 'Add attributes to products';
		break;

		default: //If no match, load admin home page for login
		$title = 'Admin Welcome';
		break; 
	}//End page switch

//Load site-wide config file
	require_once '../includes/config.incl.php';
	
//Load admin config file
	require_once('includes/admin-config.incl.php');
//Load global functions
	require_once('../includes/global-functions.incl.php');

//Load correct page
	//Check authentication
	require_once('Auth.php');
	$options = array(
		'dsn' => "mysql://unicenta:password@localhost/auth"
		);
	$pearAuth = new Auth("DB", $options, "loginFunction");
	$pearAuth->start();

//Only allow admin access to authenticated users:
if($pearAuth->checkAuth())
{
	//Load functions
	require_once('includes/admin-functions.incl.php');
	//Load header
	require_once('includes/header.incl.php');

	//Load page
	require_once("modules/$p/$p.handler.incl.php"); //Each modules has its own directory & page handler

	//Load footer
	require_once('includes/footer.incl.php');
}
