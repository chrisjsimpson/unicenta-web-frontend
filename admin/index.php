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
		case 'add-product':
		$title = "Add a product";
		$p = $p . 'incl.php';
		break;

		default:
		$title = 'Admin Welcome';
		$p = 'home.incl.php';
		break; 
	}//End page switch

//Load correct page
	//Load header
	require_once('includes/header.incl.php');

	//Load page
	require_once("modules/$p");

	//Load footer
	require_once('includes/footer.incl.php');
