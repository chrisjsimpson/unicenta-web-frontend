<?php

function getProductCategories($dbc)
{
	$results = array();

	$q = "SELECT ID, NAME, PARENTID, IMAGE 
		FROM CATEGORIES";

	$r = mysqli_query($dbc, $q);
		
	while ($row = mysqli_fetch_array($r, MYSQL_ASSOC))
	{		
	$results[] = $row;
	}

	return $results;
}//En get get product categories

function isValidCategory($dbc, $id)
{
	$id = mysqli_real_escape_string($dbc, $id);
	$q = "SELECT ID FROM CATEGORIES WHERE
		ID = '$id'";
	$r = mysqli_query($dbc, $q);

	if(mysqli_num_rows($r) == 1)
	{
		return true;
	}else{
		return false;
	}
}//End is valid category

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
		$limit = strpos($action, '&');
		//Strip the '&'and anything following it
		$action = substr($action,0, $limit);
		
		//Find next url argument '&' if exists and use as marker to strip out only module action
		$length = strpos($_SERVER['QUERY_STRING'], '&', $position);
		
		}//End get module action
	//echo '<h3>First position: ' . $position . ' </h3>';
	//echo "<h2>$action</h2>";
	return $action;
}//End getModuleAction

function getCategoryName($dbc, $id)
{
	$id = mysqli_real_escape_string($dbc, $id);

	if(isValidCategory($dbc, $id))
	{	
		$q = "SELECT NAME FROM CATEGORIES WHERE ID = '$id'";
		$r = mysqli_query($dbc, $q);	

		if(mysqli_num_rows($r) == 1)
		{

			list($catname) = mysqli_fetch_array($r);

			return $catname;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
