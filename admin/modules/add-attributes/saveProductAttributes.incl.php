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
   
 
if($_POST['submitted'])
{
	
	//Loop through attribute groups storing their stock levels
	foreach ($_POST as $attributeName=>$value) {
		
		echo "\nKey: " . $attributeName . "  Value: ";
		
		if(is_array($value)) //Submit button and hidden inputs are not arrays
		{
			foreach ($value as $key => $value) {
				echo $value . '<br />';
			} 
		}
		
	}
	
	
	
	
}else{//If $_POST is submitted, process submission
	
	echo 'No data recieved';	
}