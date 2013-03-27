<?php
//if local development mode change form data accordingly.
if($local)
	{
		echo '<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="business" value="' . PAYPAL_BUSINESS_EMAIL . '">
			<input type="hidden" name="notify_url" value="' . PAYPAL_IPN_LISTENER . '">';
	}else{

		echo '<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="business" value="' . PAYPAL_BUSINESS_EMAIL .'">';
	}//End if local development mode change form data accordingly.

	echo '
			<input type="hidden" name="cmd" value="_cart">
			<input type="hidden" name="currency_code" value="' . PAYPAL_DEFAULT_CURRENCY .'">
			<input type="hidden" name="country" value="' . PAYPAL_COUNTRY_CODE .'">
			<input type="hidden" name="handling_cart" value="'.$postage.'">
			<input type="hidden" name="rm" value="2">
			<input type="hidden" name="return_url" value="' . BASE_URL . 'thankyou"/>
			<input type="hidden" name="upload" value="1">';
	
		$n = 1;//Set paypals starting string_x
		
	foreach ($_SESSION['cart'] as $variationId=>$quantity){
		
		list($product, $attributes) = getProductDetailsFromVariationId($dbc, $variationId);		
		
		//Product name:
		echo '<input type="hidden" name="item_name_'.$n.'" value="' . $product['NAME'] . ' ';
				
			//Append product attributes to product name:
			foreach ($attributes as $attributeName => $value) {
						
					echo ' ' . $attributeName . ': ' . $value . '.';
			}
			echo '">';
		
		echo '<input type="hidden" name="item_number_'.$n.'" value="'. $variationId .'">
			 <input type="hidden" name="amount_'.$n.'" value="'.$product['SELLPRICE'].'">
			 <input type="hidden" name="quantity_'.$n.'" value="'.$quantity.'" />
			 <input type="hidden" name="shipping_'.$n.'" value="0" />';
		
		$n++;//Incriment paypals starting string_x
		
	}//End loop to add all products to cart.
	
	
	echo '<input type="submit" value="Checkout" class="cartUpload" /></form>';
	
	//End build paypal form submission!.