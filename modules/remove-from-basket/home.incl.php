<?php
/*
 * Removes the product specified in $_REQUEST['variationId']
 * from the cart, which is stored in $_SESSION['cart']['variationId']
 * 
 * Logic:
 * 			>Check $_REQUEST['variationId'] exists
 * 			>Check valid variationId
 * 			>Check if variationId exists in the cart ($_SESSION['cart']['x'])
 * 			>If all above is true, unset the variationId from the cart using
 * 			> unset($_SESSION['cart']['x']);
 * 			>Redirect user back to the shopping basket.
 */
 
 //Check $_REQUEST['variationId'] exists
 if(isset($_REQUEST['variationId']))
 {
 	$variationId = $_REQUEST['variationId'];
 	//Check valid variationId
 	if(getAttributeValuesFromVariationSetId($dbc, $variationId))
	{
		//Check if variationId exists in the cart ($_SESSION['cart']['x'])
		if(isset($_SESSION['cart'][$variationId]))
		{
			
			//Unset the variationId from the cart
			unset($_SESSION['cart'][$variationId]);
			//Redirect user back to the shopping basket:
			$url = BASE_URL . 'Basket/';
			header("Location: $url");
		}//End Check if variationId exists in the cart ($_SESSION['cart']['x'])
	}//End Check valid variationId
 }//End Check $_REQUEST['variationId'] exists
 
 /*
 * Removes product from cart using the $variationId
 * removeProductFromCart($variationId)
 */
function removeProductFromCart($variationId)
{
	if(isset($_SESSION['cart'][$variationId]))
	{
		unset($_SESSION['cart'][$variationId]);
	}
}//End removeProductFromCart($variationId)
