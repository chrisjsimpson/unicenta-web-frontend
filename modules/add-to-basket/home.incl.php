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
			addProductCart($variationId);
	}

/*
 * 
 * addProductCart()
 * 
 */

function addProductCart($variationId)
{
	//Check if product already in cart, then incriment quantity 
	if(isset($_SESSION['cart'][$variationId]))
	{
		$_SESSION['cart'][$variationId]++;
		
	}else{
		$_SESSION['cart'][$variationId] = 1;
	}//End add first instance of a product to cart
}//End addProductCart($variationId)