<?php
function load_header()
{

	//Include header image
	if(file_exists(TEMPLATE_BASE_URI . 'images/' . HEADER_IMAGE))	{
		echo '<img src="' . TEMPLATE_BASE_URL . 'images/' .HEADER_IMAGE . '" />';
	}else{
		echo '<img src="http://placehold.it/800x150" />';
	}
}

function load_primary_navigation($dbc)
{
//	$categories = array(array());
	$nav = '<nav>
	<ul>';


	//Load categories 
	$q = "SELECT NAME, ID, PARENTID FROM CATEGORIES";
	$r = mysqli_query($dbc, $q);
	
	echo '<h2>Get all categoried</h2>'. $q;

	//check each category for a parent before echoing
	while($categories = mysqli_fetch_array($r, MYSQL_ASSOC))
	{
		//Check if has parent
		if(!categoryHasParent($dbc, $categories['ID']))
		{
			//Has no parent
			echo 'here';
			$category[] = $categories['NAME'];
		}else{
			echo 'has a parent';
		}
	}//End echo each category

	print_r($category);

	//Close navigation list & <nav> html5 tag

	$nav .= '</ul></nav>';

	

}//End load_primary_navigation()

function getCategoryParentName($dbc, $id)
{
	$q = "SELECT NAME FROM CATEGORIES WHERE ID = '$id'";

	$r = mysqli_query($dbc, $q);

	list($parentName) = mysqli_fetch_array($r);

	return $parentName;
}

function categoryHasParent($dbc, $id)
{
	$q = "SELECT ID FROM CATEGORIES WHERE ID = '$id' AND
		PARENTID IS NULL";
	$r = mysqli_query($dbc, $q);
	
	if(mysqli_num_rows($r) == 0)
	{
	return true;
	}else{
	return false;
	}
}

function load_footer()
{

}
