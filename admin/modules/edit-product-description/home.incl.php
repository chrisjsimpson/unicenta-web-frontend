<?php

 $products = array(); //Initiate products array
 $products = getProductsInCategory($dbc, "any", $products);
 
 //Check for previous sucessful update:
 if(isset($_GET['successful']))
 {
 	echo '<p class="updateSuccessful">Last edit successful.</p>';
 }
 
echo "\n<ul>"; //Begining products list

//echo each product into list item
 foreach ($products as $product) {
	echo "\n<li>";
	echo '<a href="' . ADMIN_BASE_URL . '?p=edit-product-description&edit&id=';
	echo $product['ID'] . '">'. $product['NAME'];
	echo '</a></li>';//End list item with anchor
	}	

echo "\n</ul>"; //End products list
