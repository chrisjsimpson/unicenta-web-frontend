<?php
/*
This script takes the ID of a category, validates it and then deletes the category after confirming deletion.
*/

$categroryId = $_GET['id'];
$errors = array();

if(!isset($_GET['confirm']))
{
	//Show name of category for deletion:
	if(isValidCategory($dbc, $_GET['id']))
	{
		echo "\n<p>Confirm deletion of category: '";
		echo getCategoryName($dbc, $_GET['id']) . "'</p>\n";
	}//End show name of category for deletion if valid
	
	//Url to confirm deletion
	$confirmDelete = '?' . $_SERVER['QUERY_STRING'] . '&confirm';

//confirm button
echo '<a href="' . $confirmDelete . '">' . "\n";



echo '<input type="button" value="Delete" />' . "\n";
echo "</a>\n";

//Cancel button:
echo '<a href="' . ADMIN_BASE_URL . '">
	<input type="button" name="cancel" value="cancel" />
	</a>'."\n";
}else{
	//Check valid category id 
	if(isValidCategory($dbc, $_GET['id']))
	{
		//Get category name:
		$catName = getCategoryName($dbc, $_GET['id']);
		
		//Check category does not have products assigned to it
		$q_checkConstraint = "SELECT ID FROM PRODUCTS WHERE CATEGORY = '{$_GET['id']}'";		
		$r_checkConstraint = mysqli_query($dbc, $q_checkConstraint);
		
		if(mysqli_num_rows($r_checkConstraint) == 0)
		{

			$q = "DELETE FROM CATEGORIES WHERE ID = '{$_GET['id']}'";
			$r = mysqli_query($dbc, $q);

			if(mysqli_affected_rows($dbc) == 1)
			{
				echo "\n<p>'$catName' deleted sucessfully</p>";
			}//End echo if sucessful
		}else{//Tell user cannot delete category as products are associated to it.
			
			$errors[] = 'Cannot delete category as products are associated to this category
					which must first be deleted or moved to another category.';
		}
	}else{//End only process valid category ids
		$errors[] = 'Invalid or non-existant category id.';
	}
	report_errors($errors);
	
}//End ask user to confirm deletion
