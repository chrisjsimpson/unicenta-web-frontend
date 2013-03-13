<?php 

/*
	Gets primary product image and sends image back to caller 
	with image/jpg mime type
*/

define('DB', '/var/unicenta-connect.incl.php');
require_once DB;

$id = $_GET['id'];

$q = "SELECT IMAGE FROM PRODUCTS WHERE ID = '$id'";
$r = mysqli_query($dbc, $q);

list($image) = mysqli_fetch_array($r);

header("Content-type: image/jpg"); 
print($image);  
?>
