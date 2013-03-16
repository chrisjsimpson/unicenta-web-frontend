<?php

/* Default add attributes (home) page:
 * 
 * - Asks the user to specify which product they wish to add attributes to.
 * 
 */
 $products = array(); //Initiate products array
 $products = getProductsInCategory($dbc, "any", $products);
 
echo "\n<ul>"; //Beging products list

//echo each product into list item
 foreach ($products as $product) {
	echo "\n<li>";
	echo '<a href="' . ADMIN_BASE_URL . '?p=add-attributes&chooseAttributes&id=';
	echo $product['ID'] . '">'. $product['NAME'];
	echo '</a></li>';//End list item with anchor
	}	

echo "\n</ul>"; //End products list
