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
			if($variationId = getVariationIdFromRequestArray($dbc, $_REQUEST))
			{
				//If add to cart successful, redirect use to show cart
				if(addProductCart($variationId))
				{
					$url = BASE_URL . 'Basket/';
					header("Location: $url");
				}//End redirect user to view their cart
			}else{
				
				//Check valid productId is in $_REQUEST['productId'] before appending to url
				if(validProduct($dbc, $_REQUEST['productId']))
				{
					$productId = $_REQUEST['productId'];
				}
				
				//URL direct to basket including outofstock error
				$url = BASE_URL . 'Basket/Notavailable/' . $productId ;
				header("Location: $url");
				
			}//End product variation not instock
	}

print_r($_SESSION);
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
		return true;
	}else{
		$_SESSION['cart'][$variationId] = 1;
		return true;
	}//End add first instance of a product to cart
}//End addProductCart($variationId)