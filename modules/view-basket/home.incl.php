<?php

echo '<h2 id="viewBasketTitle">Your Basket</h2>';

function getProductDetailsFromVariationId($dbc, $variationId)
{
	/*
	 * 	returns two items: array of product price & stock level and a 
	 *  seccond array of its attributes
	 * 	including:
	 * 	Price, Stocklevel, attributes
	 */
	 
	 //Clean variationId
	 $variationId = cleanString($dbc, $variationId);
	 //First get product name, 
	 $q = "SELECT *
	 FROM VARIATIONSET
	 JOIN PRODUCTS ON 
	 VARIATIONSET.FK_PRODUCT_ID = PRODUCTS.ID
	 WHERE VARIATIONSET.ID = '$variationId'";

	$r = mysqli_query($dbc, $q);
	
	if($r)//If get product details succeeds
	{
		$productDetails = mysqli_fetch_array($r);	
		
	}else{
		return false;
	}//End get product details failed.
	
	//Now get product attributes 
	$q = "SELECT DISTINCT 
		ATTRIBUTE.NAME, ATTRIBUTEVALUE.VALUE 
		FROM VARIATIONSET JOIN 
		VARIATION ON 
		VARIATIONSET.ID = VARIATION.FK_VARIATION_SET 
		JOIN ATTRIBUTEVALUE ON 
		VARIATION.FK_ATTRIBUTE_VALUE = ATTRIBUTEVALUE.ID 
		JOIN ATTRIBUTE ON 
		ATTRIBUTEVALUE.ATTRIBUTE_ID = ATTRIBUTE.ID 
		JOIN ATTRIBUTEUSE ON
		ATTRIBUTE.ID = ATTRIBUTEUSE.ATTRIBUTE_ID
		WHERE VARIATIONSET.ID = '$variationId'
		ORDER  BY ATTRIBUTEUSE.LINENO";
	
	$r = mysqli_query($dbc, $q);
	
	$attributes = array(); //Initialise attributes array
	
	//Put attributes from this variation into array & return it with the $productDetails array
	if($r)
	{
		while ($attribute = mysqli_fetch_array($r)) {
			
			//NAME and VALUE are the column names of the ATTRIBUTE & ATTRIBUTEVALUE table
			$attributes[$attribute['NAME']] = $attribute['VALUE']; 
		}
		
	}else{
		return false; //ERROR Could not find variations from variationset id
	}
	
	/* If $productDetails array, and $attributes array are not expty, return them. Else return false.
	 *  (There MUST be values stores in both arrays) a product cannot have no attributes at all. 
	 */
	 if(!empty($productDetails) && !empty($attributes))
	 {
	 		return array($productDetails, $attributes);	
	 }else{
	 	return false;
	 }//End if either $productDetails or $attribues is empty, return false as failure. 
	 
}//End getProductVariationDetails($dbc, $variationId)


//Check for 'out of stock error' from add-to-basket script 
if(isset($_GET['variationNotAvailable']))
{
	echo "\n<p class\"notAvailable\">Unfortunately that product is not available.</p>";
}//End check for 'out of stock error' from add-to-basket script 


if(!cartEmpty($_SESSION))
{
	//Iniciate total price
	$totalPrice = 0;
	//Begin form
	echo "\n<form name=\"cartContents\" ";
	
	//Basket Form action
	echo 'action="#">';
	//Opening list for items in basket
	echo "\n<ul id=\"basketProductList\">";
	
	foreach ($_SESSION['cart'] as $variationId => $quantity) 
	{
		list($productDetails, $attributes) = getProductDetailsFromVariationId($dbc, $variationId);	
		//Add product price to $totalPrice:
		$totalPrice += $productDetails['SELLPRICE'] * $quantity;
		//Product image:
		echo "\n\t<img src=\"" . BASE_URL . 'includes/getImage.php?id=' . $productDetails['ID'];
		echo '" width="90" height="90" />';
		//Product name:
		echo "\n\t<li>{$productDetails['NAME']}";
		
		//Print product choices (variations) box
		echo "\n\t<div class=\"basketProductAttributes\">";
		
		echo "\n\t<dl>"; //Begin definition list for product attributes
		
		//Print quantity (This is not stored in the $attributes array from getProductDetailsFromVariationId)
		echo "\n\t\t<dt>Quantity</dt>";
		echo "\n\t\t\t<dd>$quantity</dd>";
		
		//Now display attributes returned from getProductDetailsFromVariationId
		foreach ($attributes as $attribute => $value) {
			
			echo "\n\t\t<dt>$attribute</dt>"; //Definition title (e.g. 'Color')
			echo "\n\t\t\t<dd>$value</dd>";  //Definition value (e.g. 'Blue');
		}
		
		//Display Price
		echo "\n\t\t<dt>Price</dt>"; 
		echo "\n\t\t\t<dd>&pound;{$productDetails['SELLPRICE']}</dd>";  
		
		echo "\n\t</dl>"; //End definition list for product attributes list
		//End product attributes box
		echo "</div>";
		
		//Remove from cart button
		echo "\n\t" . '<a href="' . BASE_URL . 'Basket/Remove/'. $variationId;
		echo '" title="Remove this item from your basket" >';
		echo '<input type="button" name="removeProductFromBasket" value="Remove" /></a>';
		//End Remove product from cart button
		
		echo "</li><hr />";//End list item
	}
			
	echo "\n</ul>"; //Ending list for basket list
	
	//Show cart total price:
	echo '<p class="totalCostOfItems">&pound;' . $totalPrice . '</p>';
	
	//End form
	echo "\n</form>";
	
	//Work out postage:
	$postage = 10;
	
	//Paypal Form submission
	include_once('generatePaypalCartSubmitForm.incl.php');
	//End paypal form submission
	
}else{//End echo cart contents
	echo 'No items in Basket yet.';
}//Cart is empty