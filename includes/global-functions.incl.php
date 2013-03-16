<?php
function report_errors($errors)
{
	echo '<ul>';
	foreach($errors as $error)
	{
		echo '<li>' . $error . '</li>';
	}
	echo '</ul>';
}

function cleanString($dbc, $string, $allowable_tags = null)
{
	//Remove whitespace
	$string = trim($string);

	//Remove any html
	$string = strip_tags($string, $allowable_tags);

	//Escape string for mysql insertion
	$string = mysqli_real_escape_string($dbc, $string);

	return $string;
}

function category_to_urlsafe($dbc, $id)
{
/* 
	Takes a category id, finds the name associated with it >
	converts it to a url-safe string. Returns: string. false on failure.
*/

	//Get category name from id
	$id = mysqli_real_escape_string($dbc, $id);

	$q = "SELECT NAME FROM CATEGORIES WHERE ID = '$id'";
	$r = mysqli_query($dbc, $q);
	
	//If category name found, convert to websafe.
	if(mysqli_num_rows($r) == 1)
	{
		list($categoryName) = mysqli_fetch_array($r);

		$categoryName = urlencode($categoryName);
		return $categoryName;	
	}else{
		return false;
	}//End if category not found return false

}//End category_to_websafe($dbc, $id)

function getModuleAction()
{

/*
        A module action can be thought of the name for a task to
        be performed on one of the pages (each page is a module).

        Pages are chosen by the scheme BASE_URL/?p=page-name

        Module actions (page actions) are requested in the url like so:
        
        BASE_URL/?p=page-name&moduleAction

        For example the 'delete category' module is first accessed with the url:
        BASE_URL/?p=delete-category

        An action of the delete-category module is to delete the specified category:
        BASE_URL/?p=delete-category?delete?id=x

        In the above example 'delete-category' is the page (the module)
        and 'delete' is the module action.

        The remaning 'id=x' attribute is module spesific and used by the calling module

        All this function does is strip the modle action from the url and 
        returns it. It is used primarily in the page handler for each module

*/
        //Find pos of first & 
        if(strpos($_SERVER['QUERY_STRING'], '&') == 0)
        {
                $action = 'home.incl.php';
        }else{
                $position = strpos($_SERVER['QUERY_STRING'], '&') + 1;
                //Strip beginning off query string (removing ?p=x&)
                $action= substr($_SERVER['QUERY_STRING'], $position);

                //Strip following text after seccond '&' if exists
                // e.g. 'delete&id=xxx' becomes 'delete'
                //Find position of '&'
                if(strpos($action, '&') != false)
                {
$limit =  strpos($action, '&');
                        //Strip the '&'and anything following it
                        $action = substr($action,0, $limit);
                        //Find next url argument '&' if exists and use as marker to strip out only module action
                        $length = strpos($_SERVER['QUERY_STRING'], '&', $position);
                }else{ //End if more than one '&' in url, only take the first one.

                }//End if only one '&' artument is present, process it

             }//End get module action
        return $action;
}//End getModuleAction


function getProductsInCategory($dbc, $id, &$products) //Pass $products by reference not value
{               

/*  
 Get all products in this category (and subcategoryies)
 hasSubCategories($dbc, $id); 
*/

		//Check if requesting for all products in any category
		if(strtolower($id) == 'any')
		{
          $q_products = "SELECT * FROM PRODUCTS";
         $r_products = mysqli_query($dbc, $q_products);
		 
		 	while($product = mysqli_fetch_array($r_products))
			{
				$products[] = $product;
			}//End put all products into the $products array
		}else{

                $id = mysqli_real_escape_string($dbc, $id);
                $q_products = "SELECT * FROM PRODUCTS WHERE CATEGORY = '$id'";
                $r_products = mysqli_query($dbc, $q_products);
                
                if(mysqli_num_rows($r_products) > 0)
                {
                while($product =  mysqli_fetch_array($r_products))
                {
                        array_push($products,$product);
        
                }//End check each category for products
                }
                        //Check if this product's category has a subcategory, and get products in it (repeat)
                        $q = "SELECT ID FROM CATEGORIES WHERE PARENTID = '$id'";
                        $r = mysqli_query($dbc, $q);
	        if(mysqli_num_rows($r) >  0)
        	{               
                        //For each sub category found, get it's products
                        while($subCat = mysqli_fetch_array($r))
                        {
                                getProductsInCategory($dbc, $subCat['ID'], $products);
                        }
        	}
        }//End else (if 'any' category requested, simply spit back all products in database)
                return $products;
}

function getProductNameFromId($dbc, $id)
{
	/* Returns the product name from the id */
	$id = cleanString($dbc, $id);
	
	$q = "SELECT NAME FROM PRODUCTS WHERE ID = '$id'";
	$r = mysqli_query($dbc, $q);
	
	if(mysqli_num_rows($r) == 1)
	{
		list($productName) = mysqli_fetch_array($r);
		return $productName;
	}else{
		return false;
	}
}// End getProductNameFromId($dbc, $id)

function getProductDetails($dbc, $id)
{
	/* Returns product details including:
	 * >ID	REFERENCE	CODE	CODETYPE,
	 * >NAME	PRICEBUY	PRICESELL	,
	 * >CATEGORY	TAXCAT	ATTRIBUTESET_ID,
	 * >STOCKCOST	STOCKVOLUME	IMAGE	,
	 * >ISCOM	ISSCALE	ISKITCHEN	PRINTKB,
	 * >SENDSTATUS	ISSERVICE	ATTRIBUTES
	 */
	 $id = cleanString($dbc, $id);
	 $q = "SELECT * FROM PRODUCTS WHERE ID = '$id'";
	 $r = mysqli_query($dbc, $q);
	 
	 if(mysqli_num_rows($r) == 1)
	 {
	 	return mysqli_fetch_array($r, MYSQLI_ASSOC);
	 }else{
	 	return false; //Product not found
	 }
}

function uuid($dbc)
{
	/* Returns a UUID by using mysql's engine */
	list($uuid) = mysqli_fetch_array(mysqli_query($dbc, "SELECT UUID()"));
	
	if($uuid)
	{
		return $uuid;
	}else{
		return false;
	}
	
}//End uuid($dbc)

function getAttributeNamesForProduct($dbc, $productId)
{
	$productId = cleanString($dbc, $productId);
	
	$q = "
	SELECT ATTRIBUTE.NAME
	FROM PRODUCTS
	JOIN ATTRIBUTESET ON
	PRODUCTS.ATTRIBUTESET_ID = ATTRIBUTESET.ID
	JOIN ATTRIBUTEUSE ON
	ATTRIBUTESET.ID = ATTRIBUTEUSE.ATTRIBUTESET_ID
	JOIN ATTRIBUTE ON
	ATTRIBUTEUSE.ATTRIBUTE_ID = ATTRIBUTE.ID
	WHERE PRODUCTS.ID = '$productId'
	ORDER BY LINENO";

	
	$r = mysqli_query($dbc, $q);

	while($arrtibuteName = mysqli_fetch_array($r, MYSQL_ASSOC))
	{
		$arrtibuteNames[] = $arrtibuteName;
	}
	
	foreach($arrtibuteNames as $key)
	{
		foreach($key as $value){
			$result[] = $value;
		}
	}
	return $result;
		
}// End getAttributeNamesForProduct()

function getAttributeValuesFromVariationSetId($dbc, $variationId)
{
	$variationId = cleanString($dbc, $variationId);
	
	$q = "
	SELECT VALUE AS 'Attribute'
	FROM VARIATIONSET
	JOIN VARIATION ON
	VARIATIONSET.ID = VARIATION.FK_VARIATION_SET
	JOIN ATTRIBUTEVALUE ON
	VARIATION.FK_ATTRIBUTE_VALUE = ATTRIBUTEVALUE.ID
	JOIN ATTRIBUTE ON
	ATTRIBUTEVALUE.ATTRIBUTE_ID = ATTRIBUTE.ID
	JOIN ATTRIBUTEUSE ON
	ATTRIBUTE.ID = ATTRIBUTEUSE.ATTRIBUTE_ID
	WHERE VARIATIONSET.ID = '{$variationId}'
	GROUP BY VALUE
	ORDER BY LINENO";

	$r = mysqli_query($dbc, $q);
	
	while($result = mysqli_fetch_array($r, MYSQLI_ASSOC))
	{
		$attributes[] =  $result['Attribute'];
	}
	
	return $attributes;	
	
}//getAttributeValuesFromVariationSetId($dbc, $variationId)
