<?php
/*
 * Saves the stated available product attributes in stock
 * 
 * For example: Size: Small, Colour: Blue, Price: £10, Stock: 30
 * 
 * The above is then stored in the database so that product availability \
 * can be shown per attribute selects 
 * (if someone is looking for a T-shirt size Large color red, the stock level can be checked)
 */
   
  print_r($_POST);
  
  echo '<hr />';
   
 
if($_POST['submitted'] && getProductNameFromId($dbc, $_POST['productId']))
{
	
	//Create new variation id for VARIATION table
	$variationId = uuid($dbc);
	$productId = $_POST['productId'];
	
	//Get specified sell price for this variation
	$sellPrice = $_POST['price'][0];
	//Get stock level for this variation
	$stockLevel = $_POST['stock'][0];
	
	
	//Loop through attribute groups storing their stock levels
	foreach ($_POST as $attributeName=>$value) {
				
		if(is_array($value) && $attributeName != 'price'
			&& $attributeName != 'stock') //Submit button and hidden inputs are not arrays
		{
			foreach ($value as $key => $attributeValue) {
				echo $attributeValue . '<br />';
				$attributeValue = cleanString($dbc, $attributeValue);
				
				//Insert value (e.g. colour 'blue') into VARIATIONSET table
				$q = "INSERT INTO VARIATION (FK_VARIATION_SET, FK_ATTRIBUTE_VALUE)
					  VALUES ('$variationId', '$attributeValue')";
				$r = mysqli_query($dbc, $q);
				if(!$r){ $errors[] = 'Error adding attribute values for product.';}
			}
		}
		
	}//End loop through each attribute value storing it in table VARIATIONSET using $variationId as group id.
	
	//Insert the new $variationId into VARIATION table
	$q = "INSERT INTO VARIATIONSET (ID, FK_PRODUCT_ID, STOCK_LEVEL, SELLPRICE)
			VALUES ('$variationId', '$productId', $stockLevel, $sellPrice)";
		
	$r = mysqli_query($dbc, $q);
	
	if(!$r){ $errors[] =  'Error adding new Variation set for product attributes';}	
	
	//If no errors, redirect back to the choose attributes page
	if(empty($errors))
	{
		$url = ADMIN_BASE_URL . '?p=add-attributes&chooseAttributes&id=' . $productId . '&saveSuccessful';
		//Redirect back to choose attributes page following sucessfull save product attributes
		header("Location: $url");
	}else{
		
		report_errors($errors);
	}
	
}else{//If $_POST is submitted, process submission
	
	echo 'No data recieved';	
}