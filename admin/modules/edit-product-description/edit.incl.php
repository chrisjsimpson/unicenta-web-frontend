<?php
/*
 * Provide textbox populated with existing product description and allow user to edit it.
 */

 if(isset($_GET['id']))
 {
 	$productId = $_GET['id'];
 	//Check valid product
 	if(validProduct($dbc, $productId))
	{
		$product = getProductDetails($dbc, $productId);
		
		//Title:
		echo "<h2>Edit product description for: " . $product['NAME'] . '</h2>';
		//Product code:
		echo '<p>Product code: ' . $product['CODE'] . '</p>';
		
		//Build edit product description form
		echo "\n<form name=\"editProductDescription\" action=\"";
		//Form action
		echo ADMIN_BASE_URL . '?p=edit-product-description&save"';
		echo " method=\"post\">";
		
		echo "\n\t" . '<textarea name="productDescription" rows="10" cols="22">';
		//Put current product description into textarea
					echo $product['ATTRIBUTES'];
		
		//End <textarea>
		echo '</textarea>';

		//Product id hidden input
		echo "\n\t" . '<input type="hidden" name="productId" value="' . $productId . '" />';
		echo "\n\t" . '<br /><input type="submit" name="Save" value="Save" />';
		
		//End form
		echo "\n</form>";
				
	}else{
		echo 'Invalid Product.';
	}//End invalid product

 }else{ echo 'No product id was passed.'; }//End only proceed if $_GET['id'] is set