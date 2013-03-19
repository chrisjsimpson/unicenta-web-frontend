<?php
/*
 * Adds specified product to the shopping cart via
 * $_GET or $_POST thus $_REQUEST is used
 */

 
/* Work out variationId by getting sha256sum hash of productid, and attributes
 * e.g sha256($productId . $attribute1 . $aatrinuteN);
*/

//Only proceed on valid product id

	if(isset($_REQUEST))
	{
			$variationId = getVariationIdFromRequestArray($dbc, $_REQUEST);
			echo '<br />Variatoin id initial: ' . $variationId;
			addProductCart($variationId);
	}
	

function getVariationIdFromRequestArray($dbc, $_REQUEST)
{
	/*
	 *	Returns the product variation id using the computed sha256 hash of a 
	 *  product id, and any passed attributes 
	*/
	if(isset($_REQUEST['productId']) && validProduct($dbc, $_REQUEST['productId']))
	{

		$productId = $_REQUEST['productId'];
		//Loop through $_REQUEST data to get attributes (which are prefixes with 'attribute_')
		$attributeIds = array(); //Ready to store attributeIds
		
		foreach ($_REQUEST as $key => $value) {
			
			//Only get 'attribute_x' names & values ignoring submit, and other passed data 
			if( strpos($key, 'attribute_') !== false)
			{
				//Store attribute ids into array
				$attributeIds[] = $value;
			}
		}//End put each attribute id into $attributeIds[] array
		
		//Add product id to string to be hashed
		$toBeHashed = $productId;
		
		if(!empty($attributeIds))
		{
			foreach ($attributeIds as $value) {
				//Add each attribute to the string to be hashed
				$toBeHashed .= $value;
			}
			//Sha256sum hash of productId & selected attributes
			$hash = hash('sha256', $toBeHashed);
		}
	}//End work out variationId from combination of productid, and attributes sent
	
	$hash = cleanString($dbc, $hash);
	
	
	$q = "SELECT ID FROM VARIATIONSET 
		WHERE VARIATIONSET_HASH_SHA256 = '$hash'";
	echo $q;		
	$r = mysqli_query($dbc, $q);
	
	if(list($variationId) =  mysqli_fetch_array($r))
	{
		return $variationId;
	}else{
		return false;
	}
}//End getVariationIdFromHash($dbc, $hash)

print_r($_SESSION);



/*
 * 
 * addProductCart()
 * 
 */

function addProductCart($variationId)
{
	echo '<br /> Adding variation id: ' . $variationId;
	//Check if product already in cart, then incriment quantity 
	if(isset($_SESSION['cart'][$variationId]))
	{
		echo 'already in cart.';
		$_SESSION['cart'][$variationId]++;
		print_r($_SESSION);
		
	}else{
		$_SESSION['cart'][$variationId] = 1;
		echo 'adding to cart..';
	}//End add first instance of a product to cart
}//End addProductCart($variationId)

/*
 * 
 * removeProductCart($variationId)
 * 
 *
 */
function removeProductCart($variationId)
{
	if(isset($_SESSION['cart'][$variationId]))
	{
		unset($_SESSION['cart'][$variationId]);
	}
}//End removeProductCart($variationId)