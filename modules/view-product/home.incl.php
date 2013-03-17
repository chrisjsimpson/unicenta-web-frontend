<?php
/*
 * View product page
 * Takes the $_GET['id'] product id in the form:
 * 
 * productName/Identifier 
 * 
 * 'Identifier'	is just the first two characters of the product id, for identification purposes-
 * 	- It allows a product of the same name to exist in a different category avoiding URL name conflict.  
 * 
 */

//Explode the product name / Identifier on the slash '/' into two parts
if(count($productIdentifiers = explode('/', $_GET['id'], 2)) == 2)
{
	//Clean product name
	$productName = cleanString($dbc, $productIdentifiers[0]);
	$identifyier = cleanString($dbc, $productIdentifiers[1]);
	
	//Check product exists
	$q = "SELECT ID FROM PRODUCTS WHERE NAME = '$productName'
		  AND SUBSTRING( ID, 1, 2 ) = '$identifyier'";
	$r = mysqli_query($dbc, $q);
	
	if(mysqli_num_rows($r) == 1)
	{
		list($productId) = mysqli_fetch_array($r);
		//Get product details
		displayProduct($dbc, $productId);
	}//End check product exists in database
	
	
}//ENd only process valid product view requests in format productname/Identifier
 