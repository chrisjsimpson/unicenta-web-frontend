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
}
