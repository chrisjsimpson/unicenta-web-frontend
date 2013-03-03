<?php
/*
This script takes the ID of a category, validates it and then deletes the category after confirming deletion.
*/

$categroryId ='x';
$errors = array();

if($confirmDelete)
{
	if(isValidCategory($dbc, $categoryId)
	{

	}//End only process valid category ids
}else{//End delete category 

echo "\n" . '
<form action="thispage+confirm" method="GET">' . "\n"'
<fieldset> ' . "\n" . '
<h3>Confirm deletion</h3>' . "\n" . '
<input type="button" name="confirm" value="confirm" />'."\n".'
<input type="button" name"cancel" value="cancel" />'."\n".'

</fieldset>' . "\n" . '
</form>' . "\n";

}//End ask user to confirm deletion
