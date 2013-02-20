<?php
/* This script processes a new category submission 
	it is called by add-category.incl.php
*/

echo "<h2>Saving...</h2>";

$errors = array();

//Validate category name
if(isset($_POST['category-name']))
{
	//Check length 
	if(strlen($_POST['category-name']) > 255 || strlen($_POST['category-name']) < 3)
	{
		$errors[] = 'Category name length must be between 3 and 200 characters';
	}
}else{
	$errors[] = 'Must type a category name.';
	}
//End validate category name


//Validate parent category
if(isset($_POST['parent-category']) && $_POST['parent-category'] !== '0')
{
        //Check length 
        if(strlen($_POST['parent-category']) > 255 || strlen($_POST['parent-category']) < 3)
        {
                $errors[] = 'Parent category name must be between 3 and 200 characters';
        }else{
		$parentId =  cleanString($dbc, $_POST['parent-category'], null);
	}	
}
//Insert category into database if no errors
if(empty($errors))
{
	//clean strings
	$categoryName = cleanString($dbc, $_POST['category-name'], null);

	//Check category name does not already exist
/*
	$q = "SELECT NAME FROM CATEGORIES WHERE NAME = '$categoryName' AND PARENTID IS NULL";

	
	$r = mysqli_query($dbc, $q);

	if(mysqli_num_rows($r) > 0)
	{
		$errors[] = 'Product category "' . $categoryName . '" already exists.';
		goto reportErrors;
	}
*/
	//Build intert query
	if(isset($parentId))
	{
		$q =  "INSERT INTO CATEGORIES (ID, NAME, PARENTID)
		VALUES(UUID(), '$categoryName', '$parentId')";
	}else{
		$q = "INSERT INTO CATEGORIES (ID, NAME, PARENTID)
		VALUES(UUID(), '$categoryName', NULL)";
	}
	//Execute query:
	$r = mysqli_query($dbc, $q);
	if($r)
	{
		echo "Added new category '<em>$categoryName</em>' sucessfully.";
		echo '<a href="' . ADMIN_BASE_URL . '">Go back to home.</a>';
	}else{
		echo "An error occured adding the category to the database, please try again.";
	}
}else{//Report any errors to the user
//reportErrors:
	report_errors($errors);
}//End report any errors
