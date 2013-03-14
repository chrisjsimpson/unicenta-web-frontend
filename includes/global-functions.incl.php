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
                return $products;
}
