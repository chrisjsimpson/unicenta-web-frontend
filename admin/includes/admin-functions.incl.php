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

function getAttributeValuesFromAttrId($dbc, $id)
{
	$id = cleanString($dbc, $id);
	$values = array();
	
	$q = "SELECT ID, VALUE FROM ATTRIBUTEVALUE WHERE ATTRIBUTE_ID = '$id'";
	//echo $q;
	$r = mysqli_query($dbc, $q);
	
	if(mysqli_num_rows($r) > 0)
	{
		//Put each attribute value into an array to be returned by function
		while($allValues = mysqli_fetch_array($r, MYSQLI_ASSOC))
		{
			$values[$allValues['ID']] = $allValues['VALUE'];
		}
			
		return $values;
		
	}else{
		return false;
	}
	
}// End getAttributeValuesFromAttrId($dbc, $id)
