<?php

if(isset($_POST['productDescription']) && isset($_POST['productId']))
{
	$errors = array();
	if(validProduct($dbc, $_POST['productId']))
	{
		$productId = $_POST['productId'];
		
		$productDescription = mysqli_real_escape_string($dbc, $_POST['productDescription']);
	
	
		$q = "UPDATE PRODUCTS SET ATTRIBUTES='$productDescription'
				WHERE ID = '$productId'";
				
		$r = mysqli_query($dbc, $q);
		
		if($r)
		{
			//Redirect user to edit product descriptions list with sucess alert
			$url = ADMIN_BASE_URL . '?p=edit-product-description&successful';
			header("Location: $url");
		}else{
			
			$errors[] =  'Error updating the product description: ' . mysqli_error($dbc);
		}
	
	}else{
		$errors[] = 'Invalid productId passed.';
	}//End invalid product
	
}else{
	$errors[] = 'No product description was recieved or product id.';
}//End process product description save


if(!empty($errors))
{
	report_errors($errors);
}
